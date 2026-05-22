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
    // Hapus modal lama jika ada
    const existing = document.getElementById('payroll-detail-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'payroll-detail-modal';
    modal.style.cssText =
        'position:fixed;inset:0;z-index:9998;display:flex;align-items:center;justify-content:center;';

    // Overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50';
    overlay.onclick = () => modal.remove();
    modal.appendChild(overlay);

    // Card
    const card = document.createElement('div');
    card.className =
        'relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6 z-10';
    card.style.cssText = 'max-height:90vh;overflow-y:auto;';

    // Header
    const header = document.createElement('div');
    header.className = 'flex items-center justify-between mb-5';
    header.innerHTML = `
        <h3 class="text-lg font-bold text-gray-900">Detail Slip Gaji</h3>
        <button class="text-gray-400 hover:text-gray-600 text-xl leading-none" onclick="document.getElementById('payroll-detail-modal').remove()">&times;</button>
    `;
    card.appendChild(header);

    // Body (loading placeholder)
    const body = document.createElement('div');
    body.className = 'modal-body';
    body.innerHTML = `
        <div class="text-center py-8 text-gray-400">
            <p>Memuat data...</p>
        </div>
    `;
    card.appendChild(body);

    modal.appendChild(card);
    document.body.appendChild(modal);

    return modal;
}

function renderDetailModal(modal, salary) {
    const body = modal.querySelector('.modal-body');

    const teacher = salary.teacher || {};

    // Format rupiah helper
    const rp = (val) => {
        return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
    };

    body.innerHTML = `
        <div class="space-y-4 text-sm">
            <!-- Info Guru -->
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wider">Informasi Guru</p>
                <div class="grid grid-cols-2 gap-2">
                    <div><span class="text-gray-500">Nama</span></div>
                    <div class="font-semibold text-gray-900">${teacher.name || '-'}</div>
                    <div><span class="text-gray-500">NIP</span></div>
                    <div class="font-semibold text-gray-900">${teacher.nip || '-'}</div>
                    <div><span class="text-gray-500">Email</span></div>
                    <div class="font-semibold text-gray-900 text-xs">${teacher.email || '-'}</div>
                    <div><span class="text-gray-500">Mata Pelajaran</span></div>
                    <div class="font-semibold text-gray-900">${teacher.subject || '-'}</div>
                </div>
            </div>

            <!-- Periode & Status -->
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-gray-500">Periode</span>
                    <span class="ml-2 font-semibold text-gray-900">${salary.month_name}</span>
                </div>
                <span class="px-3 py-1 rounded-lg text-white text-xs font-semibold
                    ${salary.status === 'draft' ? 'bg-gray-600' : ''}
                    ${salary.status === 'approved' ? 'bg-blue-600' : ''}
                    ${salary.status === 'paid' ? 'bg-green-600' : ''}">
                    ${salary.status_label}
                </span>
            </div>

            <hr class="border-gray-200">

            <!-- Rincian Gaji -->
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Gaji Pokok</span>
                    <span class="font-semibold text-gray-900">${rp(salary.base_salary)}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Hari Hadir</span>
                    <span class="font-semibold text-green-600">${salary.total_present_days} hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Alfa (Tidak Hadir)</span>
                    <span class="font-semibold text-red-600">${salary.total_absent_days} hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Menit Terlambat</span>
                    <span class="font-semibold text-red-600">${salary.total_late_minutes} menit</span>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Potongan -->
            <div class="space-y-2">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Potongan</p>
                <div class="flex justify-between">
                    <span class="text-gray-500">Potongan Alfa</span>
                    <span class="font-semibold text-red-600">-${rp(salary.deduction_for_absence)}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Potongan Keterlambatan</span>
                    <span class="font-semibold text-red-600">-${rp(salary.deduction_for_late)}</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-2">
                    <span class="font-semibold text-gray-700">Total Potongan</span>
                    <span class="font-semibold text-red-600">-${rp(salary.total_deduction)}</span>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Total Akhir -->
            <div class="bg-green-50 rounded-xl p-4 flex justify-between items-center">
                <span class="font-semibold text-green-800">Total Gaji Akhir</span>
                <span class="text-xl font-bold text-green-700">${rp(salary.total_salary)}</span>
            </div>

            ${salary.notes ? `
            <div class="text-xs text-gray-400 italic">
                Catatan: ${salary.notes}
            </div>
            ` : ''}
        </div>
    `;
}

// Expose ke global scope (Blade onclick)
window.approvePayroll = approvePayroll;
window.markAsPaid = markAsPaid;
window.revertToDraft = revertToDraft;
window.viewDetail = viewDetail;