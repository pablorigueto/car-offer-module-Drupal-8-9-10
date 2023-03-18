(function ($, Drupal) {

  // Settings to carousel owl.
  Drupal.behaviors.carousels = {
    attach() {
      if (!drupalSettings.carousels) {
        $(document).ready(function () {
          if (currentUrl().indexOf("/venda/") != -1) {
            // The current URL contains "/venda/"
            $('.owl-carousel').owlCarousel({
              autoplay: true,
              autoplayTimeout: 5000,
              autoplayHoverPause: true,
              thumbs: true,
              merge:true,
              responsive: {
                0:{
                  items:1,
                  nav:true,
                  loop:true,
                  dots: true,
                  center: true,
                }
              },
            });
          }
          else {
            // The current URL does not contain "/venda/"
            $('.owl-carousel').owlCarousel({
              autoplay: false,
              autoplayTimeout: 15000,
              autoplayHoverPause: true,
              margin: 5,
              responsiveClass:true,
              responsive: {
                0:{
                  items:1,
                  nav:true,
                  loop:true,
                  dots: false
                }
              },
            });
          }
        });
        drupalSettings.carousels = true;
      }
    }
  }

  // Move the div inside the grid to better layout.
  // The states from drupal didn't worked to add a class based on another field.
  Drupal.behaviors.moveDivToInside = {
    attach() {
      if (!drupalSettings.moveDivToInsideExecuted) {
        $('.selected-car-wrapper-inside').prependTo('.contact-info-wrapper');
        if ($('.contact-info-wrapper').find('.selected-car-wrapper-inside').length > 1) {
          // Remove after the append if duplicated.
          $('.contact-info-wrapper .selected-car-wrapper-inside:first').remove();
        }
        // Set the flag to indicate that the behavior has been executed for this page.
        drupalSettings.moveDivToInsideExecuted = true;
      }
    }
  };

  // Change the dots to image on owl carousel.
  Drupal.behaviors.dotsToImageAndFillSomeFields = {
    attach() {
      if (!drupalSettings.dotsToImageAndFillSomeFields) {
        $(document).ready(function () {
          if (currentUrl().indexOf("/venda/") != -1) {
            // Get all the owl-dot elements.
            var owlDots = $('.image-group .fieldset-wrapper .owl-dot');
            // Get all the images.
            var images = $('.image-group .fieldset-wrapper .owl-carousel .owl-stage .owl-item:not(.cloned) .field-item img').clone();
            // Loop through the images.
            for (var i = 0; i < images.length; i++) {
              // Get the current image.
              var image = $(images[i]);
              // Get the image source.
              var imageSrc = image.attr('src');
              // Replace the owl-dot span with the image source.
              $(owlDots[i]).html('<img src="' + imageSrc + '" class="img-dots" />');
            }
          }
        });
        drupalSettings.dotsToImageAndFillSomeFields = true;
      }
    }
  }

  // Set a default values to some fields.
  Drupal.behaviors.autoFill = {
    attach() {
      if (!drupalSettings.autoFill) {
        $(document).ready(function () {
          $(document).ready(function () {
            var defaultTitle = 'Automatic';
            var titleField = $('input[name="title[0][value]"]');
            var fieldAutoSum = $('input[name="field_summary_car[0][value]"]');
            if (titleField.val() == '') {
              titleField.val(defaultTitle);
              fieldAutoSum.val(defaultTitle);
            }
          });
        });
        drupalSettings.autoFill = true;
      }
    }
  }

  // Change the Year of all card to same element.
  Drupal.behaviors.groupYearOnHome = {
    attach() {
      if (!drupalSettings.groupYearOnHome) {
        $(document).ready(function () {
          var allCarsCards = $("#block-carsoffer-content .node-view-mode-teaser .node-content .field--name-field-start-year");
          var allCarsCardsEnd = $("#block-carsoffer-content .node-view-mode-teaser .node-content .field--name-field-end-year");
          allCarsCards.each(function (index) {
            var position = $(this);
            var children = position;
            // Modify the children of each position here.
            var childDivStart = $(allCarsCards).eq(index);
            var startYear = childDivStart.html();
            var childDiv = $(allCarsCardsEnd).eq(index);
            var endYear = childDiv.html();
            children.html(startYear + '/' + endYear);
            allCarsCardsEnd.remove();
          });
        });
        drupalSettings.groupYearOnHome = true;
      }
    }
  }

  // Make the offer button to clickable.
  Drupal.behaviors.offerBtnToClickable = {
    attach() {
      if (!drupalSettings.offerBtnToClickable) {
        $(document).ready(function () {
          var offerLink = $('#block-carsoffer-content .node-view-mode-full .field--name-field-offer-proposal .field-item a').attr('href');
          var offerLabel = $('#block-carsoffer-content .node-view-mode-full .field--name-field-offer-proposal .field__label').text();
          var newHtml = '<a href="' + offerLink + '" class="offer-class-btn"><div class="field field--name-field-offer-proposal field--type-link field--label-above"><div class="field__label">' + offerLabel + '</div></div></a>';

          $('#block-carsoffer-content .node-view-mode-full .field--name-field-offer-proposal').replaceWith(newHtml);
        });
        drupalSettings.offerBtnToClickable = true;
      }
    }
  }

  // Format the km/miles field.
  Drupal.behaviors.kmAndFormatField = {
    attach() {
      if (!drupalSettings.kmAndFormatField) {
        $(document).ready(function () {
          // Insert KM text and dot eac 3 digits.
          var kmPosition = $("#block-carsoffer-content .node-content .field--name-field-car-km-miles .field-item");
          var kmLabel = $("#block-carsoffer-content .node-content .field--name-field-car-km-miles .field__label").html();
          kmPosition.each(function (index) {
            var position = $(this);
            var kmChildDiv = position;
            // Modify the children of each position here.
            var kmChildDiv = $(kmPosition).eq(index);
            var kmToInsert = kmChildDiv.html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            kmChildDiv.html(kmToInsert + ' ' + kmLabel);
          });
        });
        drupalSettings.kmAndFormatField = true;
      }
    }
  }

  // Format the currency field.
  Drupal.behaviors.currencyFormatField = {
    attach() {
      if (!drupalSettings.currencyFormatField) {
        $(document).ready(function () {
          var brlPosition = $("#block-carsoffer-content .node-content .field--name-end-price .field-item");
          brlPosition.each(function (index) {
            var position = $(this);
            var brlChildDiv = position;
            // Modify the children of each position here.
            var brlChildDiv = $(brlPosition).eq(index);
            var brlChildDivText = brlChildDiv.html().replace(/[^\d]/g, '');
            brlChildDiv.html(currencyFormatter(brlChildDivText));
          });
        });
        drupalSettings.currencyFormatField = true;
      }
    }
  }

  // Format the license Plate field.
  Drupal.behaviors.plateFormatField = {
    attach() {
      if (!drupalSettings.plateFormatField) {
        $(document).ready(function () {
          var licensePlate = $("#block-carsoffer-content .node-view-mode-teaser .node-content .field--name-field-placa-license-plate .field-item");
          var licensePlateLabel = $("#block-carsoffer-content .node-view-mode-teaser .node-content .field--name-field-placa-license-plate .field__label").html();
          var isLicenseMobile = false;
          // Mobile device on teaser view. Remove some * from license.
          if (currentUrl().indexOf("/venda/") == -1 && deviceType() != 'computer') {
            isLicenseMobile = true;
          }

          licensePlate.each(function (index) {
            var position = $(this);
            var licensePlatePos = position;
            // Modify the children of each position here.
            var licensePlatePos = $(licensePlate).eq(index);
            var licensPlateUpper = licensePlatePos.html().toUpperCase();
            if (isLicenseMobile) {
              licensPlateUpper = licensPlateUpper.slice(0,1) + licensPlateUpper.slice(4,licensPlateUpper.length);
            }
            licensePlatePos.html(licensePlateLabel + ': ' + licensPlateUpper);
          });
        });
        drupalSettings.plateFormatField = true;
      }
    }
  }

  // Format the gearShift field.
  Drupal.behaviors.gearShiftFormatField = {
    attach() {
      if (!drupalSettings.gearShiftFormatField) {
        $(document).ready(function () {
          var gearShift = $("#block-carsoffer-content .node-view-mode-teaser .node-content .field--name-engine-gearshift-type .field-item");
          var gearShiftLabel = $("#block-carsoffer-content .node-view-mode-teaser .node-content .field--name-engine-gearshift-type .field__label").html();
          gearShift.each(function (index) {
            var position = $(this);
            var gearShiftPos = position;
            // Modify the children of each position here.
            var gearShiftPos = $(gearShift).eq(index);
            var gearShiftPosTrait = gearShiftPos.html();
            if (gearShiftPosTrait.length > 6) {
              gearShiftPos.html(gearShiftLabel + ': ' + gearShiftPosTrait.substring(0, 4));
            }
            else {
              gearShiftPos.html(gearShiftLabel + ': ' + gearShiftPosTrait);
            }
          });
        });
        drupalSettings.gearShiftFormatField = true;
      }
    }
  }

  // Format the summary field.
  Drupal.behaviors.summaryFormatField = {
    attach() {
      if (!drupalSettings.summaryFormatField) {
        $(document).ready(function () {
          var summary = $("#block-carsoffer-content .node-view-mode-teaser .field--name-field-summary-car .field-item");
          summary.each(function (index) {
            var position = $(this);
            var summaryPos = position;
            // Modify the children of each position here.
            var summaryPos = $(summary).eq(index);
            summaryPos.html(hrefUpperTrait(summaryPos));
          });
        });
        drupalSettings.summaryFormatField = true;
      }
    }
  }

  // event.which is now considered to be a legacy property and may not be supported in all browsers.
  // A more modern alternative is the event.keyCode.
  // It's to prevent words on integer fields on exposed filters.
  Drupal.behaviors.integerAcceptField = {
    attach() {
      if (!drupalSettings.integerAcceptField) {
        $(document).ready(function () {
          $("#edit-field-start-year-value, \
            #edit-field-end-year-value, \
            #edit-end-price-value-1, \
            #edit-end-price-value, \
            #edit-clt-car-sttyear-offer, \
            #edit-clt-car-endyear-offer, \
            #edit-down-offer, \
            #edit-clt-id, \
            #edit-phone-offer, \
            #edit-clt-car-id-offer\
          ").on("keypress", function (event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
              event.preventDefault();
            }
          });
        });
        drupalSettings.integerAcceptField = true;
      }
    }
  }

  // Accept only words and blank spaces on these fields.
  Drupal.behaviors.wordsAndBlankAcceptField = {
    attach() {
      if (!drupalSettings.wordsAndBlankAcceptField) {
        $(document).ready(function () {
          $("#edit-name-offer, #edit-clt-car-color-offer").on("keypress", function (event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 32 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)) {
              event.preventDefault();
            }
          });
        });
        drupalSettings.wordsAndBlankAcceptField = true;
      }
    }
  }

  // Block more then 4 digits.
  Drupal.behaviors.onlyFourAcceptField = {
    attach() {
      if (!drupalSettings.onlyFourAcceptField) {
        $(document).ready(function () {
          $("#edit-clt-car-sttyear-offer, \
            #edit-clt-car-endyear-offer, \
            #edit-field-start-year-value, \
            #edit-field-end-year-value\
          ").on('keyup', function () {
            if ($(this).val().length > 4) {
              $(this).val($(this).val().substr(0, 4));
            }
          });
        });
        drupalSettings.onlyFourAcceptField = true;
      }
    }
  }

  // Block more then 14 digits.
  Drupal.behaviors.onlyFourTeenAcceptField = {
    attach() {
      if (!drupalSettings.onlyFourTeenAcceptField) {
        $(document).ready(function () {
          $("#edit-clt-id").on('keyup', function () {
            if ($(this).val().length > 14) {
              $(this).val($(this).val().substr(0, 14));
            }
          });
        });
        drupalSettings.onlyFourTeenAcceptField = true;
      }
    }
  }

  // Block more then 11 digits.
  Drupal.behaviors.onlyElevenAcceptField = {
    attach() {
      if (!drupalSettings.onlyElevenAcceptField) {
        $(document).ready(function () {
          $("#edit-phone-offer").on('keyup', function () {
            if ($(this).val().length > 11) {
              $(this).val($(this).val().substr(0, 11));
            }
          });
        });
        drupalSettings.onlyElevenAcceptField = true;
      }
    }
  }

  // Phone format.
  Drupal.behaviors.phoneFormatField = {
    attach() {
      if (!drupalSettings.phoneFormatField) {
        $(document).ready(function () {
          $("#edit-phone-offer").on('blur', function () {
            if ($(this).val().length == 11) {
              $(this).val("(" + $(this).val().slice(0,2) + ") " + $(this).val().slice(2,7) + "-" + $(this).val().slice(7,11));
            }
            else if($(this).val().length == 10) {
              $(this).val("(" + $(this).val().slice(0,2) + ") " + $(this).val().slice(2,6) + "-" + $(this).val().slice(6,10));
            }
          });
        });
        drupalSettings.phoneFormatField = true;
      }
    }
  }

  // Block more then 10 digits.
  Drupal.behaviors.onlyTenAcceptField = {
    attach() {
      if (!drupalSettings.onlyTenAcceptField) {
        $(document).ready(function () {
          $("#edit-bday-offer").on('keyup', function () {
            if ($(this).val().length > 10) {
              $(this).val($(this).val().substr(0, 10));
            }
          });
        });
        drupalSettings.onlyTenAcceptField = true;
      }
    }
  }

  // Set the BRL currency after the field is changed on Price field.
  Drupal.behaviors.setBdayFormatField = {
    attach() {
      if (!drupalSettings.setBdayFormatField) {
        $(document).ready(function () {
          $("#edit-bday-offer").on("change", function () {
            var dateStr = $(this).val();
            var formattedDate = dateStr.substr(0, 2) + "/" + dateStr.substr(2, 2) + "/" + dateStr.substr(4, 4);
            $(this).val(formattedDate);
          });
        });
        drupalSettings.setBdayFormatField = true;
      }
    }
  }

  // Set the BRL currency after the field is changed on Price field.
  Drupal.behaviors.setCurrencyFormatField = {
    attach() {
      if (!drupalSettings.setCurrencyFormatField) {
        $(document).ready(function () {
          $("#edit-end-price-value-1, \
            #edit-end-price-value \
          ").on("change", function () {
            var value = $(this).val();
            $(this).val(currencyFormatter(value));
          });
        });
        drupalSettings.setCurrencyFormatField = true;
      }
    }
  }

  // Set the BRL currency after page load on Price field. It's
  // solved the initial page and after search page.
  Drupal.behaviors.setCurrencyOnLoadFormatField = {
    attach() {
      if (!drupalSettings.setCurrencyOnLoadFormatField) {
        $(document).ready(function () {
          $("#edit-end-price-value-1, \
            #edit-end-price-value\
          ").each(function () {
            var value = $(this).val();
            $(this).val(currencyFormatter(value));
          });
        });
        drupalSettings.setCurrencyOnLoadFormatField = true;
      }
    }
  }

  // Clear the field value to avoid NaN on integer field or bad function.
  Drupal.behaviors.cleanFieldsOnClick = {
    attach() {
      if (!drupalSettings.cleanFieldsOnClick) {
        $(document).ready(function () {
          $("#edit-end-price-value-1, \
            #edit-end-price-value, \
            #edit-down-offer, \
            #edit-clt-car-kmormiles-offer, \
            #edit-phone-offer, \
            #edit-bday-offer\
          ").on('click', function () {
            // Clear the field's value
            $(this).val('');
          });
        });
        drupalSettings.cleanFieldsOnClick = true;
      }
    }
  }

  // Format the km or miles fields from offer.
  Drupal.behaviors.kmOrMilesFormatFieldOffer = {
    attach() {
      if (!drupalSettings.kmOrMilesFormatFieldOffer) {
        $(document).ready(function () {
          $("#edit-clt-car-kmormiles-offer").on("change", function () {
            var value = $(this).val();
            $(this).val(kmOrMilesFormat(value));
          });
        });
        drupalSettings.kmOrMilesFormatFieldOffer = true;
      }
    }
  }

  // Set the BRL currency after the field is changed on Price field.
  Drupal.behaviors.currencySetChangedFormatFieldOffer = {
    attach() {
      if (!drupalSettings.currencySetChangedFormatFieldOffer) {
        $(document).ready(function () {
          $("#edit-down-offer\
          ").on("change", function () {
            var value = $(this).val();
            $(this).val(currencyOfferFormatter(value));
          });
        });
        drupalSettings.currencySetChangedFormatFieldOffer = true;
      }
    }
  }

  // Redirect using the title href link when user click on an teaser node.
  Drupal.behaviors.makeTheSecBlockClickable = {
    attach() {
      if (!drupalSettings.makeTheSecBlockClickable) {
        $(document).ready(function () {
          $(".second-block-class").click(function (event) {
            event.preventDefault();
            var firstHref = $(this).find('a').attr('href');
            window.location.href = firstHref;
          });
        });
        drupalSettings.makeTheSecBlockClickable = true;
      }
    }
  }

  // Redirect using the title href link when user click using
  // mousedown (scroll) on an teaser node.
  Drupal.behaviors.mouseDownClickable = {
    attach() {
      if (!drupalSettings.mouseDownClickable) {
        $(document).ready(function () {
          $(document).on('mousedown', '.second-block-class', function (e) {
            // Check if is the middle button.
            if(e.which === 2) {
              var firstHref = $(this).find("a").first().attr("href");
              window.open(firstHref, '_blank');
            }
          });
        });
        drupalSettings.mouseDownClickable = true;
      }
    }
  }

  // Format the id CPF and CNPJ to offers.
  Drupal.behaviors.idFormatField = {
    attach() {
      if (!drupalSettings.idFormatField) {
        $(document).ready(function () {
          $("#edit-clt-id").on("input", function () {
            var value = $(this).val();
            if (value.length <= 11) { // format as CPF
              $(this).val(value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4"));
            }
            else { // format as CNPJ
              $(this).val(value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5"));
            }
          });
        });
        drupalSettings.idFormatField = true;
      }
    }
  }

  // Custom behaviors.
  Drupal.behaviors.groupFieldsCustom = {
    attach() {
      if (!drupalSettings.groupFieldsCustom) {
        $(document).ready(function () {
          // Mobile device.
          if (deviceType() != 'computer') {
            // Move the Price button if it's a mobile device.
            var priceGroup = $('#price-group-id');
            // find the #second-group-id element
            var secondGroup = $('#second-group-id');
            // attach the #price-group-id element before the #second-group-id element
            priceGroup.insertBefore(secondGroup);

            // Splice in two columns the buttons of car full view.
            // select all the child divs inside the #second-group-id div
            var childDivs = $('#second-group-id').children('div');

            // slice the child divs into two groups
            var firstGroup = childDivs.slice(0, 5);
            var secondGroup = childDivs.slice(5);

            // create a new parent div and wrap the two groups of divs
            var newParentDiv = $('<div>').addClass('child-class-mobile-buttons-fullview');
            firstGroup.wrapAll(newParentDiv);
            secondGroup.wrapAll(newParentDiv);
          }
        });
        drupalSettings.groupFieldsCustom = true;
      }
    }
  }

  // Add style year fields without css included.
  Drupal.behaviors.styleYearWithoutCssIncluded = {
    attach() {
      if (!drupalSettings.styleYearWithoutCssIncluded) {
        $(document).ready(function () {
          $("#year-side-form-class").css({"display": "flex",
          "gap": "2rem"});
        });
        drupalSettings.styleYearWithoutCssIncluded = true;
      }
    }
  }

  // Add a class on submit button of offers.
  Drupal.behaviors.addClassOnSubmitBtnOffers = {
    attach() {
      if (!drupalSettings.addClassOnSubmitBtnOffers) {
        $(document).ready(function () {
          if (deviceType() == 'computer') {
            $('#edit-checkbox-with-car-offer').change(function () {
              var submitBtn = $('.path-offer #edit-submit');
              if (this.checked) {
                submitBtn.addClass('btn-100-width-class');
                submitBtn.removeClass('button');
              } else {
                submitBtn.removeClass('btn-100-width-class');
                submitBtn.addClass('button');
              }
            });
          }
        });
        drupalSettings.addClassOnSubmitBtnOffers = true;
      }
    }
  }

  // Returns current URL.
  function currentUrl() {
    return $(location).attr('href');
  }

  // Returns device type.
  function deviceType() {
    var deviceType = 'computer';
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
      deviceType = 'mobile';
    }
    return deviceType;
  }

  // Get the label from price to identify the currency.
  function currencyFormatter(value) {
    var labelPrice = $("#block-carsoffer-content .node-content .field--name-end-price .field__label").html();
    if (labelPrice.includes("R$")) {
      return Number(value).toLocaleString("pt-BR", {style: "currency", currency: "BRL"});
    }
    else if(labelPrice.includes("$")) {
      return Number(value).toLocaleString("en-US", {style: "currency", currency: "USD"});
    }
  }

  // Format the currency offer.
  function currencyOfferFormatter(value) {
    return Number(value).toLocaleString("pt-BR", {style: "currency", currency: "BRL"});
  }

  // Format the km or miles offer.
  function kmOrMilesFormat(value) {
    // Convert the value to a number and format it with periods
    value = Number(value).toLocaleString('en-US', {minimumFractionDigits: 0});
    return value.replace(',', '.');
  }

  // Set only text to uppercase, elements keep as the same.
  function hrefUpperTrait(summaryPos) {
    var originalHtml = summaryPos.html();
    var anchorText = summaryPos.html().match(/<a[^>]*>(.*?)<\/a>/)[1];
    var newFormatText = anchorText.toUpperCase();
    // If the summary has more than 60 characters, limited to 60 and set 3 dots.
    var limitedText = newFormatText.length > 34 ? newFormatText.substring(0, 34 - 3) + "..." : newFormatText;
    return originalHtml.replace(/(<a[^>]*>)(.*?)(<\/a>)/, "$1" + limitedText + "$3");
  }

})(jQuery, Drupal);
