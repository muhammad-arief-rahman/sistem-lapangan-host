@push('root')
    <div id="{{ $id }}" aria-modal="true" aria-hidden="true"
        class="bg-black/15 backdrop-blur-xs fixed inset-0  max-w-full max-h-full z-20 transition-all duration-100 aria-hidden:invisible group aria-hidden:opacity-0"
        data-close>
        <div class="grid place-items-center size-full fixed inset-0 modal-container overflow-auto max-h-screen"
            id="modal-{{ $id }}-container">
            <div
                class="md:p-12 p-6 rounded-md bg-white max-w-4xl w-[calc(100%-2rem)] relative m-12 group-[[aria-hidden='true']]:scale-95 duration-100">
                <button class="absolute top-2 right-2 size-8 rounded-full cursor-pointer grid place-items-center bg-white"
                    data-close>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x size-4">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
                {{ $slot }}
            </div>
        </div>
    </div>
@endpush

<script>
    (() => {
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.querySelector("#{{ $id }}[aria-modal='true']");
            const openModal = document.querySelectorAll("[data-modal='{{ $id }}']");
            const closeModal = modal.querySelectorAll("[data-close]");
            const modalContainer = document.getElementById("modal-{{ $id }}-container");

            function closeModalHandler() {
                modal.ariaHidden = 'true';

                const event = new CustomEvent('toggle', {
                    detail: {
                        id: '{{ $id }}',
                        action: 'close'
                    }
                });

                modal.dispatchEvent(event);
            }

            function openModalHandler() {
                modal.ariaHidden = 'false';

                const event = new CustomEvent('toggle', {
                    detail: {
                        id: '{{ $id }}',
                        action: 'open'
                    }
                });

                modal.dispatchEvent(event);
            }

            openModal.forEach((button) => {
                button.addEventListener("click", openModalHandler);
            });

            closeModal.forEach((button) => {
                button.addEventListener("click", closeModalHandler);
            });

            modalContainer.addEventListener("click", (event) => {
                event.stopPropagation();

                if (event.target === modalContainer) {
                    closeModalHandler();
                }
            });

            const isOpen = {{ $open ? 'true' : 'false' }};

            if (isOpen) {
                openModalHandler();
            }

            window.addEventListener('openModal', (event) => {
                if (event?.detail?.id === '{{ $id }}') {
                    openModalHandler();
                }
            })
        });
    })()
</script>
