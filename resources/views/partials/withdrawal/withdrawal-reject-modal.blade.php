@push('root')

    <x-modal id="withdrawal-reject-modal">
        <form data-use-submit-alert="Yakin ingin menolak penarikan ini? Setelah ditolak, penarikan tidak dapat diproses ulang."
            action="{{ route('dashboard.withdrawal.reject') }}" class="flex flex-col gap-4" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="withdrawal_id" id="withdrawal_id">

            <div class="form-control">
                <h2 class="text-lg font-semibold text-primary">Tolak Penarikan</h2>
                <p class="text-sm text-zinc-500">
                    Silahkan berikan alasan penolakan penarikan ini. Pastikan untuk memberikan informasi yang jelas
                    kepada pengguna.
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <div class="form-control">
                    <label for="notes">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border border-zinc-200 rounded-lg px-4 min-h-24"
                        placeholder="Masukkan catatan" required>{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i>
                    <span>Tolak Penarikan</span>
                </button>
            </div>
        </form>
    </x-modal>
@endpush

@push('scripts')
    <script>
        (() => {
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.reject-withdrawal').forEach(button => {
                    console.log('Adding click event listener to button:', button);

                    button.addEventListener('click', () => {
                        const withdrawalId = button.getAttribute('data-id');

                        document.querySelectorAll('input[name="withdrawal_id"]').forEach(
                            input => {
                                input.value = withdrawalId;
                            });

                        dispatchEvent(new CustomEvent('openModal', {
                            detail: {
                                id: 'withdrawal-reject-modal',
                            }
                        }));
                    });
                });
            })
        })()
    </script>
@endpush
