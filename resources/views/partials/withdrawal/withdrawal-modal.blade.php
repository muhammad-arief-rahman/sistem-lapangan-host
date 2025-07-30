@push('root')
    <x-modal id="withdrawal-modal">
        <form use-submit-alert="Yakin melakukan penarikan? Admin akan memproses penarikan ini secepatnya."
            action="{{ route('dashboard.withdrawal.store') }}" class="flex flex-col gap-4" method="POST">
            @csrf
            <div class="form-control">
                <h2 class="text-lg font-semibold text-primary">Buat Penarikan Baru</h2>
                <p class="text-sm text-zinc-500">
                    Silahkan isi form berikut untuk membuat penarikan saldo. Pastikan tujuan penarikan sudah benar.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="form-control">
                    <label for="account_type" class="form-label">Tujuan Penarikan</label>

                    <select name="account_type" id="account_type" class="input input-main" required
                        placeholder="Pilih tujuan penarikan">
                        <option value="" disabled selected>Pilih tujuan penarikan</option>
                        @forelse ($withdrawMethods as $category => $method)
                            <optgroup label="{{ $category }}">
                                @forelse ($method as $item)
                                    <option value="{{ $item->name }}"
                                        {{ old('account_type') == $item->name ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada metode penarikan untuk kategori ini</option>
                                @endforelse
                            </optgroup>
                        @empty
                            <option value="" disabled>Tidak ada metode penarikan</option>
                        @endforelse

                    </select>

                    @error('account_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="account_name" class="form-label">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" id="account_name"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required
                        placeholder="Masukkan nama pemilik rekening" value="{{ old('account_name') }}">

                    @error('account_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="account_number" class="form-label">Nomor Penarikan</label>
                    <input type="text" name="account_number" id="account_number"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required
                        placeholder="Masukkan nomor rekening penarikan" value="{{ old('account_number') }}">

                    @error('account_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="amount" class="form-label">Jumlah Penarikan</label>
                    <div class="grid grid-cols-4 gap-2">
                        <input type="number" name="amount" id="amount"
                            class="w-full border border-zinc-200 rounded-lg px-4 h-12 col-span-3" required
                            max="{{ $cardData->balance }}" placeholder="Masukkan jumlah penarikan"
                            value="{{ old('amount') }}">

                        <button id="max-amount-button" type="button" class="btn btn-primary h-full" onclick="">
                            Semua
                        </button>
                    </div>

                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" class="btn btn-primary">
                    Buat Penarikan
                </button>
            </div>
        </form>
    </x-modal>
@endpush
