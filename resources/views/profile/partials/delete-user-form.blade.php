<section>

    <div class="mb-4">
        <h4 class="fw-semibold">
            Hapus Akun
        </h4>

        <p class="text-muted mb-0">
            Setelah akun dihapus, seluruh data yang berkaitan dengan akun ini
            akan dihapus secara permanen.
        </p>
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Hapus Akun
    </button>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('profile.destroy') }}">

                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Konfirmasi Hapus Akun
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <p class="text-muted">
                            Tindakan ini tidak dapat dibatalkan.
                            Masukkan password untuk menghapus akun.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                            </label>

                            <input type="password" name="password" id="password" class="form-control">

                            @if ($errors->userDeletion->has('password'))
                                <div class="text-danger mt-2">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Hapus Akun
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</section>
