// Create simple datatables
document.querySelectorAll(".simple-datatable").forEach(function (table) {
    new simpleDatatables.DataTable(table, {
        perPageSelect: [5, 10, 15, 20, 25],
        perPage: 15,
        searchable: true,
        sortable: true,
        labels: {
            placeholder: "Cari...",
            perPage: "baris per halaman",
            noRows: "Tidak ada data yang ditemukan",
            info: "Menampilkan {start} sampai {end} dari {rows} baris",
        },
        layout: {
            top: "{search}",
            bottom: "{info} {select} {pager}",
        },
        // add a wrapper element to the table
    });
});

window.useToggle = function (toggleId, callback) {
    window.addEventListener(`use-toggle-${toggleId}`, function (e) {
        const detail = e.detail;

        if (typeof callback === "function") {
            const { isOpen, element } = detail;

            callback(isOpen, element);
        }
    });
};

$(document).ready(function () {
    document
        .querySelectorAll("[data-use-submit-alert]")
        .forEach(function (element) {
            const message = element.getAttribute("data-use-submit-alert");

            if (message == null || message == "null") {
                return;
            }

            element.addEventListener("submit", function (event) {
                event.preventDefault();

                Swal.fire({
                    title: "Perhatian",
                    text: message || "Apakah anda yakin ingin melanjutkan?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "var(--color-primary)",
                    cancelButtonColor: "var(--color-zinc-500)",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        element.submit();
                    }
                });
            });
        });

    function createToggleEvent(element) {
        return new CustomEvent(`use-toggle-${element.dataset.useToggle}`, {
            detail: {
                isOpen: element.dataset.isOpen === "true",
                element: element,
            },
        });
    }

    document.querySelectorAll("[data-use-toggle]").forEach(function (element) {
        element.dataset.isOpen = element.dataset.isOpen || "false";

        if (element.dataset.isOpen === "true") {
            dispatchEvent(createToggleEvent(element));
        }

        element.style.visibility = "visible";

        if (!element.dataset.useToggle) {
            console.warn("Element with data-use-toggle must have a value.");
            return;
        }

        const buttons = document.querySelectorAll(
            `[data-toggle="${element.dataset.useToggle}"]`,
        );

        buttons.forEach(function (button) {
            button.addEventListener("click", function () {
                element.dataset.isOpen =
                    element.dataset.isOpen === "true" ? "false" : "true";

                dispatchEvent(createToggleEvent(element));
            });
        });
    });

    document
        .querySelectorAll("[data-use-input-preview-text]")
        .forEach(function (element) {
            const inputElement = document.querySelector(
                `[name='${element.dataset.useInputPreviewText}']`,
            );

            if (!inputElement) return;

            inputElement.addEventListener("change", function () {
                const file = inputElement.files[0];

                if (!file) return;

                element.textContent = file.name;
            });
        });

    document
        .querySelectorAll("[data-use-image-preview]")
        .forEach(function (element) {
            const inputElement = document.querySelector(
                `[name='${element.dataset.useImagePreview}']`,
            );
            if (!inputElement) return;
            inputElement.addEventListener("change", function () {
                const file = inputElement.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    element.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        });

    document
        .querySelectorAll("[data-use-submit-form]")
        .forEach(function (element) {
            const form = document.querySelector(
                `#${element.dataset.useSubmitForm}`,
            );

            if (!form) return;

            form.submit();
        });
});
