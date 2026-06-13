/**
 * ---------------------------------------------------------------
 * Payroll Workflow — Web Admin Dashboard
 * Endpoints: /api/v1/admin/payroll/
 * ---------------------------------------------------------------
 * STEP 1: Approve (draft → approved)
 * STEP 2: Mark as Paid (approved → paid)
 * STEP 4: Revert to Draft (approved → draft)
 * STEP 3: View Detail Modal (fetch on click)
 * ---------------------------------------------------------------
 *
 * Digunakan oleh: resources/views/admin/salary.blade.php
 * Import via:     resources/js/app.js
 * Pattern:        onclick di Blade → fungsi di-expose ke window
 * ---------------------------------------------------------------
 */

// =============================================================================
// CSRF TOKEN — dari meta tag di layout admin
// =============================================================================

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

// =============================================================================
// TOAST NOTIFICATION (dibuat dinamis, tidak bergantung HTML)
// =============================================================================

let toastContainer = null;

function ensureToastContainer() {
    if (toastContainer) return toastContainer;

    toastContainer = document.createElement('div');
    toastContainer.id = 'payroll-toast-container';
    toastContainer.style.cssText =
        'position:fixed;top:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:0.75rem;';
    document.body.appendChild(toastContainer);
    return toastContainer;
}

function showToast(message, type = 'success') {
    const container = ensureToastContainer();

    const bgClass = type === 'success' ? 'bg-green-600' : 'bg-red-600';

    const toast = document.createElement('div');
    toast.className = `${bgClass} text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium`;
    toast.style.cssText =
        'animation:fadeIn 0.3s ease;min-width:280px;max-width:400px;';
    toast.textContent = message;

    container.appendChild(toast);

    // Auto-remove setelah 4 detik
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            toast.remove();
            if (container.children.length === 0) {
                container.remove();
                toastContainer = null;
            }
        }, 300);
    }, 4000);
}

// =============================================================================
// HELPERS — loading & restore button
// =============================================================================

/**
 * Set loading state pada tombol spesifik (by ID).
 * @param {HTMLElement} btn
 * @returns {string} Original text sebelum diubah
 */
function setButtonLoading(btn) {
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    const originalText = btn.textContent;
    btn.textContent = 'Processing...';
    return originalText;
}

/**
 * Restore tombol ke state semula.
 */
function restoreButton(btn, originalText) {
    btn.textContent = originalText;
    btn.disabled = false;
    btn.classList.remove('opacity-50', 'cursor-not-allowed');
}

// =============================================================================
// DOM UPDATE — update badge & render ulang tombol aksi
// =============================================================================

function updateStatusBadge(salaryId, status) {
    const badge = document.getElementById(`status-badge-${salaryId}`);
    if (!badge) return;

    const colorMap = {
        'draft': 'bg-gray-600',
        'approved': 'bg-blue-600',
        'paid': 'bg-green-600',
    };

    const labelMap = {
        'draft': 'Draft',
        'approved': 'Approved',
        'paid': 'Paid',
    };

    badge.textContent = labelMap[status] || status;
    badge.className = `px-3 py-1 rounded-lg text-white text-xs font-semibold ${colorMap[status] || 'bg-gray-600'}`;
}

function renderActionButtons(salaryId, status) {
    const actionsDiv = document.getElementById(`actions-${salaryId}`);
    if (!actionsDiv) return;

    let html = '';

    if (status === 'draft') {
        html = `
            <button id="approve-btn-${salaryId}"
                    onclick="approvePayroll(${salaryId})"
                    class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Approve
            </button>
        `;
    } else if (status === 'approved') {
        html = `
            <button id="paid-btn-${salaryId}"
                    onclick="markAsPaid(${salaryId})"
                    class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Mark as Paid
            </button>
            <button id="revert-btn-${salaryId}"
                    onclick="revertToDraft(${salaryId})"
                    class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Revert
            </button>
        `;
    }
    // paid → kosong (readonly)

    actionsDiv.innerHTML = html;
}

// =============================================================================
// STEP 1 — APPROVE PAYROLL  (draft → approved)
// =============================================================================

async function approvePayroll(salaryId) {
    const confirmed = confirm(
        'Approve payroll ini?\n\n' +
        'Setelah di-approve, payroll tidak bisa dihitung ulang.\n' +
        'Pastikan data absensi sudah benar.'
    );

    if (!confirmed) return;

    const btn = document.getElementById(`approve-btn-${salaryId}`);
    const originalText = btn ? setButtonLoading(btn) : 'Approve';

    try {
        const response = await fetch(`/api/v1/admin/payroll/${salaryId}/approve`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal approve payroll.');
        }

        // === SUCCESS ===
        updateStatusBadge(salaryId, 'approved');
        renderActionButtons(salaryId, 'approved');
        showToast(result.message || 'Payroll berhasil di-approve.', 'success');

    } catch (error) {
        if (btn) restoreButton(btn, originalText);
        showToast(error.message || 'Terjadi kesalahan.', 'error');
    }
}

// =============================================================================
// STEP 2 — MARK AS PAID  (approved → paid)
// =============================================================================

async function markAsPaid(salaryId) {
    const confirmed = confirm(
        'Tandai payroll ini sebagai sudah dibayar?\n\n' +
        'Setelah ditandai paid, data tidak bisa diubah lagi.'
    );

    if (!confirmed) return;

    const btn = document.getElementById(`paid-btn-${salaryId}`);
    const originalText = btn ? setButtonLoading(btn) : 'Mark as Paid';

    try {
        const response = await fetch(`/api/v1/admin/payroll/${salaryId}/paid`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal menandai sebagai dibayar.');
        }

        // === SUCCESS ===
        updateStatusBadge(salaryId, 'paid');
        renderActionButtons(salaryId, 'paid');
        showToast(result.message || 'Payroll ditandai sudah dibayar.', 'success');

    } catch (error) {
        if (btn) restoreButton(btn, originalText);
        showToast(error.message || 'Terjadi kesalahan.', 'error');
    }
}

// =============================================================================
// STEP 4 — REVERT TO DRAFT  (approved → draft)
// =============================================================================

async function revertToDraft(salaryId) {
    const confirmed = confirm(
        'Kembalikan payroll ke draft?\n\n' +
        'Payroll bisa dihitung ulang setelah dikembalikan.'
    );

    if (!confirmed) return;

    const btn = document.getElementById(`revert-btn-${salaryId}`);
    const originalText = btn ? setButtonLoading(btn) : 'Revert';

    try {
        const response = await fetch(`/api/v1/admin/payroll/${salaryId}/revert`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal mengembalikan ke draft.');
        }

        // === SUCCESS ===
        updateStatusBadge(salaryId, 'draft');
        renderActionButtons(salaryId, 'draft');
        showToast(result.message || 'Payroll dikembalikan ke draft.', 'success');

    } catch (error) {
        if (btn) restoreButton(btn, originalText);
        showToast(error.message || 'Terjadi kesalahan.', 'error');
    }
}


// =============================================================================
// STEP 3 — VIEW DETAIL PAYROLL (Modal)
// =============================================================================

async function viewDetail(salaryId) {
    // Tampilkan modal loading
    const modal = openDetailModal();

    try {
        const response = await fetch(`/api/v1/admin/payroll/${salaryId}`, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal mengambil detail payroll.');
        }

        renderDetailModal(modal, result.data.salary);

    } catch (error) {
        modal.querySelector('.modal-body').innerHTML = `
            <div class="text-center py-8 text-red-500">
                <p class="font-semibold">Gagal Memuat Data</p>
                <p class="text-sm mt-1">${error.message}</p>
            </div>
        `;
    }
}
function openDetailModal() {

    const existing =
        document.getElementById('payroll-detail-modal');

    if (existing) existing.remove();

    const modal = document.createElement('div');

    modal.id = 'payroll-detail-modal';

    modal.innerHTML = `
        <div
            class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="
                background:rgba(0,0,0,.75);
                z-index:9999;
            "
        >
            <div
                class="panel shadow-lg"
                style="
                    width:90%;
                    max-width:800px;
                    max-height:85vh;
                    display:flex;
                    flex-direction:column;
                "
            >
                <div
                    class="panel-header border-bottom d-flex justify-content-between align-items-center"
                >
                    <div>
                        <h3 class="fw-bold mb-1">
                            Detail Slip Gaji
                        </h3>

                        <div class="text-secondary">
                            Informasi penggajian guru
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn btn-outline-light"
                        onclick="closePayrollModal()"
                    >
                        ✕
                    </button>
                </div>

                    <div class="panel-body modal-body"
                    style="
                        overflow-y:default;
                        overflow-x:hidden;
                    ">
                    <div class="text-center py-5">
                        Memuat data...
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    document.body.style.overflow = 'hidden';

    return modal;
}
function renderDetailModal(modal, salary) {
    const body = modal.querySelector('.modal-body');
    const teacher = salary.teacher || {};
    const rp = (val) =>
        'Rp ' + Number(val || 0).toLocaleString('id-ID');
    let statusClass = 'bg-secondary';
    if (salary.status === 'approved')
        statusClass = 'bg-primary';
    if (salary.status === 'paid')
        statusClass = 'bg-success';
    body.innerHTML = `
        <div class="row g-3">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <span>Informasi Payroll</span>
                    <span class="badge ${statusClass}">
                        ${salary.status_label}
                    </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm text-white mb-0">
                                <tr>
                                    <td width="120">Nama</td>
                                    <td>${teacher.name ?? '-'}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>${teacher.nip ?? '-'}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>${teacher.email ?? '-'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm text-white mb-0">
                                <tr>
                                    <td width="120">Mapel</td>
                                    <td>${teacher.subject ?? '-'}</td>
                                </tr>
                                <tr>
                                    <td>Periode</td>
                                    <td>${salary.month_name}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <span class="badge ${statusClass}">
                                            ${salary.status_label}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-header">
                        Ringkasan Kehadiran
                    </div>
                    <div class="panel-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4 class="text-success fw-bold mb-1">
                                    ${salary.total_present_days}
                                </h4>
                                <div class="small text-secondary">
                                    Hari Hadir
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-warning fw-bold mb-1">
                                    ${salary.total_late_minutes}
                                </h4>
                                <div class="small text-secondary">
                                    Menit Terlambat
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-danger fw-bold mb-1">
                                    ${salary.total_absent_days}
                                </h4>
                                <div class="small text-secondary">
                                    Tidak Hadir
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-header">
                        Rincian Penggajian
                    </div>
                    <div class="panel-body py-3 px-4">
                        <table class="table table-dark table-sm align-middle">
                            <tbody>
                                <tr>
                                    <td>Gaji Pokok</td>
                                    <td class="text-end text-success fw-bold">
                                        ${rp(salary.base_salary)}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Potongan Alfa</td>
                                    <td class="text-end text-danger fw-bold">
                                        -${rp(salary.deduction_for_absence)}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Potongan Keterlambatan</td>
                                    <td class="text-end text-danger fw-bold">
                                        -${rp(salary.deduction_for_late)}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Potongan</td>
                                    <td class="text-end text-warning fw-bold">
                                        -${rp(salary.total_deduction)}
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold">
                                        Total Gaji Akhir
                                    </td>
                                    <td class="text-end fw-bold">
                                        ${rp(salary.total_salary)}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
}
function closePayrollModal() {

    const modal =
        document.getElementById('payroll-detail-modal');

    if (modal) {
        modal.remove();
    }

    document.body.style.overflow = 'auto';
}
// Expose ke global scope (Blade onclick)
window.approvePayroll = approvePayroll;
window.markAsPaid = markAsPaid;
window.revertToDraft = revertToDraft;
window.viewDetail = viewDetail;
window.closePayrollModal = closePayrollModal;
