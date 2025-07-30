@push('root')
    <x-modal id="withdrawal-approve-modal">
        <form use-submit-alert="Yakin ingin memproses penarikan ini? Setelah diproses, penarikan tidak dapat dibatalkan."
            action="{{ route('dashboard.withdrawal.process') }}" class="flex flex-col gap-4" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="withdrawal_id" id="withdrawal_id">

            <div class="form-control">
                <h2 class="text-lg font-semibold text-primary">Setujui Penarikan</h2>
                <p class="text-sm text-zinc-500">
                    Silahkan lakukan penarikan dan upload bukti pengiriman kepada rekening yang tertera.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-4">
                    <div class="form-control">
                        <div class="form-label">Nama</div>
                        <div class="form-value" id="withdrawal_user_name"></div>
                    </div>
                    <div class="form-control">
                        <div class="form-label">Jumlah Penarikan</div>
                        <div class="form-value" id="withdrawal_amount"></div>
                    </div>
                    <div class="form-control">
                        <div class="form-label">Tujuan Penarikan</div>
                        <div class="form-value">
                            <span id="withdrawal_account_type"></span>
                            <span>, a.n </span>
                            <span id="withdrawal_account_name"></span>
                            <span id="withdrawal_account_number"></span>
                        </div>
                    </div>
                    <div class="form-control">
                        <div class="form-label">Diajukan Pada</div>
                        <div class="form-value" id="withdrawal_created_at"></div>
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="form-control">
                        <label class="form-label">Bukti Pengiriman</label>
                        <input type="file" name="proof" id="proof" accept="image/*"
                            class="input input-main h-full pl-0" required>

                        <img src="https://placehold.co/1600x900?text=Upload%20Bukti%20Pengiriman" alt="Burung"
                            class="aspect-video rounded-lg object-cover" data-use-image-preview="proof">


                        @error('proof')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label for="notes">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full border border-zinc-200 rounded-lg px-4 min-h-24"
                            placeholder="Masukkan catatan (opsional)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="flex justify-end mt-4">
                <button type="submit" class="btn btn-primary">
                    Setujui Penarikan
                </button>
            </div>
        </form>
    </x-modal>
@endpush

@push('scripts')
    <script>
        (() => {
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.process-withdrawal').forEach(button => {
                    button.addEventListener('click', () => {
                        // Get Data
                        const withdrawalId = button.getAttribute('data-id');
                        const amount = button.getAttribute('data-amount');
                        const name = button.getAttribute('data-name');
                        const accountType = button.getAttribute('data-account-type');
                        const accountName = button.getAttribute('data-account-name');
                        const accountNumber = button.getAttribute('data-account-number');
                        const createdAt = button.getAttribute('data-created-at');

                        // Set Data to Modal
                        document.getElementById('withdrawal_id').value = withdrawalId;
                        document.getElementById('withdrawal_user_name').textContent = name;
                        document.getElementById('withdrawal_amount').textContent = amount;
                        document.getElementById('withdrawal_account_type').textContent =
                            accountType;
                        document.getElementById('withdrawal_account_name').textContent =
                            accountName;
                        document.getElementById('withdrawal_account_number').textContent =
                            accountNumber;
                        document.getElementById('withdrawal_created_at').textContent =
                        createdAt;

                        dispatchEvent(new CustomEvent('openModal', {
                            detail: {
                                id: 'withdrawal-approve-modal',
                            }
                        }));
                    });
                });
            })
        })()
    </script>
@endpush
