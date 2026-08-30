const btnSubInscription = document.getElementById("btnSubInscription");
const formErrorSummary = document.getElementById('formErrorSummary');
const formErrorMessage = document.getElementById('formErrorMessage');

function showFormError(message, field = null) {
  formErrorMessage.textContent = message;
  formErrorSummary.classList.remove('d-none');
  formErrorSummary.focus({ preventScroll: true });
  formErrorSummary.scrollIntoView({ behavior: 'smooth', block: 'center' });

  if (field) {
    field.classList.add('is-invalid');
    window.setTimeout(() => field.focus({ preventScroll: true }), 350);
  }
}

function clearFormError() {
  formErrorSummary.classList.add('d-none');
  formErrorMessage.textContent = '';
}

document.addEventListener("DOMContentLoaded", function () {

    
  
    const formInscription = document.getElementById("formInscription");

    formInscription.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', () => {
            field.classList.remove('is-invalid');
            if (field.required && field.value.trim() !== '' && field.checkValidity()) {
                field.classList.add('field-valid');
            } else {
                field.classList.remove('field-valid');
            }
        });
        field.addEventListener('change', () => field.classList.remove('is-invalid'));
    });

    formInscription.addEventListener("submit", function (event) {
        clearFormError();
        formInscription.classList.add('was-validated');

        if (!formInscription.checkValidity()) {
            event.preventDefault();
            const firstInvalidField = formInscription.querySelector(':invalid');
            showFormError('Completa los campos obligatorios resaltados antes de continuar.', firstInvalidField);
            return;
        }

        btnSubInscription.disabled = true;
        btnSubInscription.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Registrando inscripción...';
        // Realiza la validación personalizada aquí
        if (!validarCamposInscription()) {
            event.preventDefault(); // Detiene el envío del formulario si la validación falla
            btnSubInscription.disabled = false;
            btnSubInscription.textContent = 'Inscribirme Ahora';
        }
    });

    function validarCamposInscription() {
        const selectCategoryRadioButtons = document.querySelector('input[name="category_inscription_id"]:checked');
        const selectedRadioCategoryInscription = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
        const selectedRadioPaymentMethod = document.querySelector('input[type="radio"][name="payment_method"]:checked');

        // Función para validar campo de archivo de FilePond
        function validarArchivoFilePond(inputId, mensajeError) {
            const inputArchivo = document.getElementById(inputId);
            const filePondInstance = FilePond.find(inputArchivo);

            if (filePondInstance.getFiles().length === 0) {
                showFormError(mensajeError, inputArchivo);
                return false;
            }

            return true;
        }

        if (selectedRadioCategoryInscription === null) {
            showFormError("Selecciona una categoría para continuar.", categoryRadioButtons[0] || null);
            return false;
        }

        if (selectedRadioCategoryInscription.dataset.requiresDocument === '1') {
            if (!validarArchivoFilePond('document_file', "Debe adjuntar documento probatorio de categoría (Título, Constancia, Carnet profesional).")) {
                return false;
            }
        }

        if (selectedRadioPaymentMethod === null) {
            showFormError("Selecciona un método de pago.", document.getElementById('payment_method_transfer'));
            return false;
        }

        if(selectedRadioPaymentMethod.value === 'Transferencia/Depósito') {
          if (selectCategoryRadioButtons.dataset.requiresVoucher === '1') {
              if (!validarArchivoFilePond('voucher_file', "Debe adjuntar un comprobante de transferencia o depósito")) {
                  return false;
              }
          }
        }

        if(selectedRadioCategoryInscription.dataset.usesSpecialCode === '1' && document.getElementById('specialcode_verify').value === ''){
            showFormError('Valida el código de la cuota especial antes de continuar.', document.getElementById('specialcode'));
            return false;
        }

        return true; // La validación pasa
    }


});

// Obtén todos los elementos radio y checkboxes
const categoryRadioButtons = document.querySelectorAll('input[type="radio"][name="category_inscription_id"]');
const accompanistCheckboxes = document.querySelectorAll('input[type="checkbox"][name="accompanist"]');
const paymentotalElement = document.getElementById('paymentotal');

// Función para calcular el precio total
function calculateTotalPrice() {
  let totalPrice = 0;

  // Suma los valores de los radios seleccionados
  categoryRadioButtons.forEach(radio => {
    if (radio.checked) {
      const catPrice = parseFloat(radio.getAttribute('data-catprice'));
      totalPrice += catPrice;
    }
  });

  // Suma el valor del checkbox si está marcado
  accompanistCheckboxes.forEach(checkbox => {
    const dvAccompanist = document.getElementById('dv_accompanist');
    const inputsaccomp = dvAccompanist.querySelectorAll('input');
    const selectsaccomp = dvAccompanist.querySelectorAll('select');

    if (checkbox.checked) {
      const catPrice = parseFloat(checkbox.getAttribute('data-catprice'));
      
      //IF category 9 not add price

      const selectedRadioCategory = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
      if(selectedRadioCategory && selectedRadioCategory.dataset.waivesAccompanistFee === '1'){
        totalPrice += 0;
      }else{
        totalPrice += catPrice;
      }

      //remove class d-none in dv_accompanist
      dvAccompanist.classList.remove('d-none');
      inputsaccomp.forEach(input => {
        input.setAttribute('required', 'required');
      });
      selectsaccomp.forEach(select => {
        select.setAttribute('required', 'required');
      });


    }else{
        inputsaccomp.forEach(input => {
          input.value = '';
        });
        selectsaccomp.forEach(select => {
          select.selectedIndex = 0;
        });

        //add class d-none in dv_accompanist
        dvAccompanist.classList.add('d-none');
        inputsaccomp.forEach(input => {
            input.removeAttribute('required');
        });
        selectsaccomp.forEach(select => {
            select.removeAttribute('required');
        });
    }
  });

  if(totalPrice == 0){

  }


  // Actualiza el elemento HTML con el precio total
  paymentotalElement.textContent = totalPrice; // Ajusta el formato según necesites
}

// Agrega un event listener para los cambios en los radios y checkboxes
categoryRadioButtons.forEach(radio => {
  radio.addEventListener('change', handleCategoryRadioButtons);
  radio.addEventListener('change', calculateTotalPrice);
  radio.addEventListener('change', updateSelectedCategoryStyle);
  radio.addEventListener('change', clearFormError);
});

document.querySelectorAll('.category-row').forEach(row => {
  row.addEventListener('click', event => {
    // Los controles y sus etiquetas ya gestionan el cambio de forma nativa.
    // Evita alternar dos veces el checkbox al pulsar el texto "Acompañante".
    if (event.target.closest('button, input, label, select, a')) return;
    const selectable = row.querySelector('input[type="radio"], input[type="checkbox"]');
    if (!selectable) return;

    if (selectable.type === 'checkbox') {
      selectable.checked = !selectable.checked;
    } else {
      selectable.checked = true;
    }
    selectable.dispatchEvent(new Event('change', { bubbles: true }));
  });
});

function updateSelectedCategoryStyle() {
  document.querySelectorAll('.category-row').forEach(row => {
    const selectable = row.querySelector('input[type="radio"], input[type="checkbox"]');
    row.classList.toggle('is-selected', Boolean(selectable && selectable.checked));
  });
}

accompanistCheckboxes.forEach(checkbox => {
  checkbox.addEventListener('change', calculateTotalPrice);
  checkbox.addEventListener('change', updateSelectedCategoryStyle);
});

// Calcula el precio total inicial
calculateTotalPrice();
updateSelectedCategoryStyle();

// Obtén los elementos del DOM
const dvDocumentFile = document.getElementById('dv_document_file');
const inputDocumentFile = document.getElementById('document_file');
const dvSpecialCode = document.getElementById('dv_specialcode');
const inputSpecialCode = document.getElementById('specialcode');
const btnValidateSpecialCode = document.getElementById('validate_specialcode');
const btnClearSpecialCode = document.getElementById('clear_specialcode');
const specialCodeVerify = document.getElementById('specialcode_verify');
const descriptionSpecialCode = document.getElementById('sms_valid_vc');
const dv_payment = document.getElementById('dv_payment');

const inputVoucherFile = document.getElementById('voucher_file');

// Función para manejar el clic categoryRadioButtons
function handleCategoryRadioButtons(){
    const selectedCategory = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
    const requiresDocument = selectedCategory.dataset.requiresDocument === '1';
    const usesSpecialCode = selectedCategory.dataset.usesSpecialCode === '1';
    const showsPayment = selectedCategory.dataset.showsPayment === '1';

    //Mostrar divs que se necesitan
    dvDocumentFile.classList.remove('d-none');
    dv_payment.classList.remove('d-none');

    const cprequired = document.getElementById('cprequired');

    if(showsPayment){
      dv_payment.classList.remove('d-none');
    }else{
      dv_payment.classList.add('d-none');
    }

    // Handle CP Required
    cprequired.classList.toggle('d-none', selectedCategory.dataset.requiresVoucher !== '1');


    if(requiresDocument){

      //Document file required
      dvDocumentFile.classList.remove('d-none');
      inputDocumentFile.setAttribute('required', 'required');

      //Special code required not validation
      dvSpecialCode.classList.add('d-none');
      inputSpecialCode.value = '';
      inputSpecialCode.removeAttribute('required');
      inputSpecialCode.removeAttribute('readonly');
      descriptionSpecialCode.textContent = '';
      specialCodeVerify.value = '';
      btnValidateSpecialCode.classList.remove('d-none');
      btnClearSpecialCode.classList.add('d-none');

    }else if(usesSpecialCode){

        cprequired.classList.add('d-none');

        // El código especial sustituye al documento probatorio.
        dvDocumentFile.classList.add('d-none');
        inputDocumentFile.removeAttribute('required');

        //Special code required validation
        dvSpecialCode.classList.remove('d-none');
        inputSpecialCode.setAttribute('required', 'required');
        inputSpecialCode.removeAttribute('readonly');
        selectedCategory.setAttribute('data-catprice', selectedCategory.dataset.originalPrice);
        selectedCategory.closest('tr').querySelector('.category-price').textContent = '00';
        descriptionSpecialCode.textContent = '';
        specialCodeVerify.value = '';
        btnValidateSpecialCode.classList.remove('d-none');
        btnClearSpecialCode.classList.add('d-none');
      }else{

        //Document file not required
        dvDocumentFile.classList.add('d-none');
        inputDocumentFile.removeAttribute('required');

        //Special code required not validation
        dvSpecialCode.classList.add('d-none');
        inputSpecialCode.value = '';
        inputSpecialCode.removeAttribute('required');
        inputSpecialCode.removeAttribute('readonly');
        descriptionSpecialCode.textContent = '';
        specialCodeVerify.value = '';
        btnValidateSpecialCode.classList.remove('d-none');
        btnClearSpecialCode.classList.add('d-none');
    }
}

//if  clic in radio invoice if value is yes add class in dv_invoice_info
const dv_invoice = document.getElementById('dv_invoice');
const dvInvoiceInfo = document.getElementById('dv_invoice_info');
const inputInvoice = document.querySelectorAll('input[type="radio"][name="invoice"]');
const inputInvoiceRuc = document.getElementById('invoice_ruc');
const inputInvoiceSocialReason = document.getElementById('invoice_social_reason');
const inputInvoiceAddress = document.getElementById('invoice_address');
const inputCountry = document.getElementById('inputCountry');
const invoiceNo = document.getElementById('invoice_no');

function updateInvoiceAvailability() {
    const selectedCategory = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
    const isValidatedFreeSpecialCode = selectedCategory
        && selectedCategory.dataset.usesSpecialCode === '1'
        && specialCodeVerify.value === 'valid'
        && dv_payment.classList.contains('d-none');
    const canRequestInvoice = inputCountry.value === 'Perú' && !isValidatedFreeSpecialCode;

    dv_invoice.classList.toggle('d-none', !canRequestInvoice);

    if (!canRequestInvoice) {
        invoiceNo.checked = true;
        inputInvoiceRuc.value = '';
        inputInvoiceSocialReason.value = '';
        inputInvoiceAddress.value = '';
        handleInvoice();
    }
}

inputCountry.addEventListener('change', updateInvoiceAvailability);

inputInvoiceRuc.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 11);
    this.setCustomValidity('');
    if (this.value.length === 11 && !isValidPeruvianRuc(this.value)) {
        this.setCustomValidity('El RUC ingresado no es válido.');
    }
});

function isValidPeruvianRuc(ruc) {
    if (!/^\d{11}$/.test(ruc)) return false;
    if (!['10', '15', '16', '17', '20'].includes(ruc.slice(0, 2))) return false;
    const weights = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
    const sum = weights.reduce((total, weight, index) => total + Number(ruc[index]) * weight, 0);
    let checkDigit = 11 - (sum % 11);
    if (checkDigit >= 10) checkDigit -= 10;
    return Number(ruc[10]) === checkDigit;
}

inputInvoice.forEach(radio => {
    radio.addEventListener('change', handleInvoice);
});

function handleInvoice(){
    const selectedValueInvoice = document.querySelector('input[type="radio"][name="invoice"]:checked').value;
    if(selectedValueInvoice === 'si'){
        dvInvoiceInfo.classList.remove('d-none');
        inputInvoiceRuc.setAttribute('required', 'required');
        inputInvoiceSocialReason.setAttribute('required', 'required');
        inputInvoiceAddress.setAttribute('required', 'required');
    }else{
        dvInvoiceInfo.classList.add('d-none');
        inputInvoiceRuc.removeAttribute('required');
        inputInvoiceSocialReason.removeAttribute('required');
        inputInvoiceAddress.removeAttribute('required');
    }
}

//validate specialcode when click validate_specialcode button
btnValidateSpecialCode.addEventListener('click', function(){

  //valida si el campo esta vacio
  if(inputSpecialCode.value === ''){
    showFormError('Ingresa un código especial para validarlo.', inputSpecialCode);
      return false;
  }

  clearFormError();
  btnValidateSpecialCode.disabled = true;
  btnValidateSpecialCode.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Validando';

  const radioCategory = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
  const txtPriceSpecialCode = radioCategory.closest('tr').querySelector('.category-price');
    //verifica si el existe via ajax javascript
  const url = baseurl + '/validate-specialcode';
  const code = inputSpecialCode.value;

  const xhr = new XMLHttpRequest();
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Configura el tipo de contenido

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);

        if (response.success) {
          txtPriceSpecialCode.textContent = Math.floor(response.price);
          inputSpecialCode.setAttribute('readonly', 'readonly');
          descriptionSpecialCode.innerHTML = '<span class="text-success">'+response.message+'</span>'
          btnClearSpecialCode.classList.remove('d-none');
          btnValidateSpecialCode.classList.add('d-none');
          btnValidateSpecialCode.disabled = false;
          btnValidateSpecialCode.textContent = 'Validar';
          specialCodeVerify.value = 'valid';
          radioCategory.setAttribute('data-catprice', Math.floor(response.price));

          

          // El código especial reemplaza el documento probatorio. La forma de
          // pago solo se oculta cuando el código no requiere ningún pago.
          dvDocumentFile.classList.add('d-none');
          inputDocumentFile.removeAttribute('required');

          if (response.payment_required === 'Si') {
            dv_payment.classList.remove('d-none');
          } else {
            dv_invoice.classList.add('d-none');
            dv_payment.classList.add('d-none');
          }
          updateInvoiceAvailability();


        } else {
          descriptionSpecialCode.innerHTML = '<span class="text-danger">'+response.message+'</span>';
          txtPriceSpecialCode.textContent = '00';
          inputSpecialCode.removeAttribute('readonly');
          specialCodeVerify.value = '';
          radioCategory.setAttribute('data-catprice', '0.00');
          btnValidateSpecialCode.disabled = false;
          btnValidateSpecialCode.textContent = 'Validar';
          inputSpecialCode.classList.add('is-invalid');
        }

        calculateTotalPrice();

      } else {
        btnValidateSpecialCode.disabled = false;
        btnValidateSpecialCode.textContent = 'Validar';
        showFormError('No pudimos validar el código. Inténtalo nuevamente.', inputSpecialCode);
      }
    }
  };

  // Configura los datos a enviar en la solicitud POST
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const params = `code=${code}&_token=${token}`;

  xhr.send(params);

});

btnClearSpecialCode.addEventListener('click', function(){
    const selectedCategory = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
    const txtPriceSpecialCode = selectedCategory.closest('tr').querySelector('.category-price');
    inputSpecialCode.value = '';
    txtPriceSpecialCode.textContent = '00';
    inputSpecialCode.removeAttribute('readonly');
    descriptionSpecialCode.textContent = '';
    btnClearSpecialCode.classList.add('d-none');
    btnValidateSpecialCode.classList.remove('d-none');
    specialCodeVerify.value = '';
    inputSpecialCode.classList.remove('is-invalid', 'field-valid');
    selectedCategory.setAttribute('data-catprice', selectedCategory.dataset.originalPrice);

    //Mostrar divs que se necesitan
    dvDocumentFile.classList.remove('d-none');
    dv_invoice.classList.remove('d-none');
    dv_payment.classList.remove('d-none');
    updateInvoiceAvailability();

    calculateTotalPrice();
});

const inputPaymentMethod = document.querySelectorAll('input[type="radio"][name="payment_method"]');
const dvTranfer = document.getElementById('dv_tranfer');
const dvCard = document.getElementById('dv_card');

inputPaymentMethod.forEach(radio => {
    radio.addEventListener('change', handlePaymentMethod);
});

function handlePaymentMethod(){
    const selectedValuePaymentMethod = document.querySelector('input[type="radio"][name="payment_method"]:checked').value;
    if(selectedValuePaymentMethod === 'Transferencia/Depósito'){
        dvTranfer.classList.remove('d-none');
        dvCard.classList.add('d-none');
        //enable btnSubInscription
        btnSubInscription.disabled = false;
    }else{
        dvTranfer.classList.add('d-none');
        dvCard.classList.remove('d-none');
        //disable btnSubInscription
        btnSubInscription.disabled = true;
    }
}

const selectedCategory = document.querySelector('input[type="radio"][name="category_inscription_id"]:checked');
if (selectedCategory) {
  handleCategoryRadioButtons();
}
handleInvoice();
handlePaymentMethod();
updateInvoiceAvailability();


const locale_es = {
  labelIdle: 'Arrastra y suelta tus archivos o <span class="filepond--label-action">Selecciona</span>',
  labelFileProcessing: 'Subiendo',
  labelFileProcessingComplete: 'Subida completada',
  labelTapToCancel: 'clique para cancelar',
  labelTapToRetry: 'clique para reenviar',
  labelTapToUndo: 'clique para deshacer',
};

FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

const inputIds = ["document_file", "voucher_file"];

inputIds.forEach((inputId) => {
  const inputElement = document.getElementById(inputId);
  FilePond.create(inputElement, {
      labelIdle: locale_es.labelIdle,
      labelFileProcessing: locale_es.labelFileProcessing,
      labelFileProcessingComplete: locale_es.labelFileProcessingComplete,
      labelTapToCancel: locale_es.labelTapToCancel,
      labelTapToRetry: locale_es.labelTapToRetry,
      labelTapToUndo: locale_es.labelTapToUndo,
      acceptedFileTypes: ['application/pdf', 'image/jpeg', 'image/png'],
      maxFileSize: '10MB',
      labelFileTypeNotAllowed: 'Formato de archivo no permitido',
      fileValidateTypeLabelExpectedTypes: 'Usa PDF, JPG o PNG',
      onaddfilestart: () => {
        btnSubInscription.disabled = true;
        btnSubInscription.textContent = 'Subiendo archivo... Espere por favor';
      },
      onprocessfile: () => {
        btnSubInscription.textContent = 'Inscribirme Ahora';
        handlePaymentMethod();
      },
      onprocessfileerror: () => {
        btnSubInscription.textContent = 'Inscribirme Ahora';
        handlePaymentMethod();
        showFormError('No se pudo cargar el archivo. Verifica el formato, el tamaño y vuelve a intentarlo.', inputElement);
      }
  });
});

FilePond.setOptions({
  server: {
      url: baseurl + '/upload',
      headers: {
          'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
      },
  },
});
