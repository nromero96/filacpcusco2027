document.addEventListener('DOMContentLoaded', function () {
    //print ficha inscripcion with printjs and html2canvas
    document
    .querySelector(".btnprintficha")
    .addEventListener("click", function (event) {

        event.preventDefault();

        const elementToCapture = document.querySelector(".ficha-inscripcion");

        const ignoreElements = function (element) {
            // Retorna true para excluir el elemento con la clase 'actionstatus', 'btnprintficha', 'btneditinsc'
            return element.classList.contains("actionstatus") || element.classList.contains("btnprintficha") || element.classList.contains("btneditinsc");

        };

        html2canvas(elementToCapture, {
            ignoreElements: ignoreElements,
        }).then(function (canvas) {
            const image = canvas.toDataURL("image/png");

            printJS({
                printable: image,
                type: "image",
                onPrintDialogClose: function () {
                    // Aquí puedes realizar acciones adicionales después de que se cierra el diálogo de impresión
                },
            });
        });
    });



});





