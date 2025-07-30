@extends('layout.dashboard')

@section('title', 'Pembayaran')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="[
            ['Pembayaran' => route('dashboard.payment.index')],
            ['Detail Pembayaran' => route('dashboard.payment.show', $paymentDetail->id)],
        ]" />
        <h2 class="text-lg font-medium ">Pembayaran Booking</h2>
        <div class="grid @3xl:grid-cols-2 gap-6">
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6 h-fit @container">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-4">
                        <h3 class="text-lg font-semibold text-primary">
                            Pesanan "{{ $paymentDetail->payment->booking->field->name }}"
                        </h3>
                        <div class="grid @lg:grid-cols-3 gap-4">
                            <div class="form-control">
                                <span class="form-label">Tanggal Pesanan</span>
                                <span class="form-value">
                                    {{ $paymentDetail->payment->booking->getBookingDateString() }}
                                </span>
                            </div>
                            <div class="flex flex-col gap-2 col-span-2">
                                <span class="form-label">Waktu Pertandingan</span>
                                <span class="form-value">
                                    {{ $paymentDetail->payment->booking->fieldSchedule->getScheduleDateString() }}
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <h3 class="text-lg font-semibold text-primary">
                            Detail Pembayaran
                        </h3>
                        <div class="grid @lg:grid-cols-3 gap-4">
                            <div class="form-control">
                                <span class="form-label">Jumlah</span>
                                <span class="form-value">{{ format_rp($paymentDetail->amount) }}</span>
                            </div>
                            <div class="flex flex-col gap-2 col-span-2">
                                <span class="form-label">Status</span>
                                <span class="form-value">
                                    @if ($paymentDetail->status === 'pending')
                                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                                    @elseif ($paymentDetail->status === 'completed')
                                        <span class="badge badge-success">Pembayaran Selesai</span>
                                    @elseif ($paymentDetail->status === 'failed')
                                        <span class="badge badge-error">Pembayaran Gagal</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($paymentDetail->status === 'pending')
                        <div class="flex flex-col gap-4">
                            <p class="text-sm text-zinc-500">
                                Silahkan lakukan pembayaran melalui metode yang telah disediakan. Setelah melakukan
                                pembayaran,
                                silahkan klik tombol "Konfirmasi Pembayaran" untuk mengonfirmasi pembayaran Anda.
                            </p>
                        </div>
                    @elseif ($paymentDetail->status === 'completed')
                        <div class="flex flex-col gap-4">
                            <p class="text-sm text-zinc-500">
                                Pembayaran Anda telah berhasil. Terima kasih telah melakukan pembayaran.
                            </p>
                        </div>
                    @elseif ($paymentDetail->status === 'failed')
                        <div class="flex flex-col gap-4">
                            <p class="text-sm text-zinc-500">
                                Pembayaran Anda gagal. Ini dapat terjadi karena pesanan yang gagal dibayar atau
                                pembayaran yang tidak berhasil.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6 h-fit">
                <div class="flex flex-col gap-2 mb-4">
                    <h3 class="text-lg font-semibold text-primary ">
                        Jendela Pembayaran
                    </h3>


                    @if ($paymentDetail->status === 'completed')
                        <div class="border border-green-300 bg-green-50 p-4 rounded flex gap-2">
                            <div class="size-6 grid place-items-center">
                                <i class="fa-solid fa-circle-check text-green-700"></i>
                            </div>
                            <p class="text-sm text-green-700">
                                Pembayaran telah berhasil. Terima kasih telah melakukan pembayaran.
                            </p>
                        </div>
                    @elseif ($paymentDetail->status === 'pending')
                        <div class="border border-red-300 bg-red-50 p-4 rounded flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-info-icon lucide-info size-4 mt-0.5 shrink-0 text-red-500">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                            <p class="text-sm text-red-500">
                                Mohon untuk tidak menutup halaman ini selama proses pembayaran berlangsung. Jika jendela
                                pembayaran tidak muncul, silahkan klik tombol "Bayar Sekarang" di bawah ini untuk membuka
                                jendela
                                pembayaran.
                            </p>
                        </div>
                    @endif
                </div>

                @if ($paymentDetail->status === 'pending')
                    <div id="snap-container" class="w-full rounded bg-zinc-50 border border-zinc-200">
                        <div class="[&:has(~_iframe)]:hidden grid place-items-center h-full p-4">
                            <div class="max-w-sm flex flex-col gap-4 items-center">
                                <p class="text-sm text-zinc-500 mb-4 text-center">
                                    Jendela pembayaran tertutup. Silahkan klik tombol "Bayar Sekarang" untuk membuka jendela
                                    pembayaran.
                                </p>
                                <button id="pay-button"
                                    class="bg-primary text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                                    Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('head')
    <script type="text/javascript" src="{{ env('MIDTRANS_SNAP_ENDPOINT') }}"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endpush

@if ($paymentDetail->status === 'pending')
    @push('scripts')
        <script>
            let status = ''

            function embedWindow() {
                snap.embed('{{ $snapToken }}', {
                    embedId: 'snap-container',
                    // Optional
                    onSuccess: function(result) {
                        const {
                            finish_redirect_url
                        } = result;
                        status = result.status;
                        window.location.href = finish_redirect_url;
                    },
                    // Optional
                    onPending: function(result) {
                        console.log("PENDING", result);
                    },
                    // Optional
                    onError: function(result) {
                        /* You may add your own js here, this is just example */
                        console.log("ERROR", result);
                    }
                });
            }

            document.querySelector('#pay-button').addEventListener('click', embedWindow)

            embedWindow();

            // Confirm page reload
            window.addEventListener('beforeunload', function(event) {
                // Show confirmation dialog
                if (status !== "settlement") {
                    event.preventDefault();
                    event.returnValue = '';
                }
            });
        </script>
    @endpush
@endif
