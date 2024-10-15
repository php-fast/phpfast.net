$(document).ready(function () {
  // Show file input dialog when clicking "Browse File" div
  function dragMedia() {
    $("#browse-button").click(function () {
      $("#file-input").click();
    });

    // Handle file selection and display preview
    $("#file-input").change(function (e) {
      let file = e.target.files[0];
      if (file) {
        let fileReader = new FileReader();
        fileReader.onload = function (event) {
          // Show preview image and file info
          $("#image-preview").attr("src", event.target.result);
          $("#image-name").text(file.name);
          $("#image-size").text((file.size / (1024 * 1024)).toFixed(2) + " MB");

          // Toggle areas
          $("#upload-area").hide();
          $("#preview-area").show();
        };
        fileReader.readAsDataURL(file);
      }
    });

    // Replace image
    $("#replace-button").click(function () {
      $("#file-input").click();
    });

    // Remove image and reset the upload area
    $("#remove-button").click(function () {
      $("#file-input").val("");
      $("#upload-area").show();
      $("#preview-area").hide();
    });

    // Drag and drop file upload
    $("#upload-area").on("dragover", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass("border-blue-500");
    });

    $("#upload-area").on("dragleave", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass("border-blue-500");
    });

    $("#upload-area").on("drop", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass("border-blue-500");

      let file = e.originalEvent.dataTransfer.files[0];
      if (file) {
        let fileReader = new FileReader();
        fileReader.onload = function (event) {
          // Show preview image and file info
          $("#image-preview").attr("src", event.target.result);
          $("#image-name").text(file.name);
          $("#image-size").text((file.size / (1024 * 1024)).toFixed(2) + " MB");

          // Toggle areas
          $("#upload-area").hide();
          $("#preview-area").show();
        };
        fileReader.readAsDataURL(file);
      }
    });
  }

  // Function to initialize select2 for all select elements
  function initializeSelect2() {
    $("select").each(function () {
      var options = {
        theme: "classic",
        placeholder: $(this).attr("placeholder"),
        allowClear: true,
      };

      // Check if the select has the class "single-select"
      if ($(this).hasClass("single-select")) {
        options.minimumResultsForSearch = Infinity; // Hide search box
      }

      // Initialize Select2 with the options
      $(this).select2(options);
    });
  }

  // Function to update visibility of delete buttons
  function updateDeleteButtons() {
    const rowCount = $("#form-country .row").length;
    console.log(rowCount);
    if (rowCount === 1) {
      $(".delete-row-btn").hide();
    } else {
      $(".delete-row-btn").show();
    }
  }

  // Call the function initially to apply Select2 and update delete buttons
  initializeSelect2();
  updateDeleteButtons();

  // Event handler for adding new row
  $("#add-row-btn").click(function () {
    const newRow = `
      <div class="row flex w-full flex-wrap md:flex-nowrap md:gap-6 mt-4">
        <div class="fieldset w-full md:w-1/2">
          <div class="field mb-4">
            <select multiple placeholder="Select country" data-allow-clear="1"
              class="select-country w-full rounded-lg border border-gray-300 focus:ring focus:ring-blue-500 focus:border-blue-500">
              <option value="AF">Afghanistan</option>
              <option value="AL">Albania</option>
              <option value="DZ">Algeria</option>
              <option value="US">United States</option>
              <option value="CA">Canada</option>
              <option value="GB">United Kingdom</option>
              <option value="AU">Australia</option>
              <option value="IN">India</option>
              <option value="JP">Japan</option>
              <option value="FR">France</option>
              <option value="VN">Vietnam</option>
            </select>
          </div>
        </div>
        <div class="fieldset w-full md:w-1/2">
          <div class="field floating-label mb-4 relative">
            <label for="price" class="block mb-2 font-medium text-sm leading-5 text-gray-900">Price, $</label>
            <input type="text" placeholder="Enter price..." required
              class="price-input w-full rounded-lg border border-gray-300 focus:ring focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>
        <!--btn Delete row -->
        <div class="delete-row-container">
          <button type="button" class="delete-row-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M13.7501 3.73182V3.92081C14.5457 3.99365 15.3347 4.08942 16.1165 4.20748C16.4059 4.25119 16.6944 4.29796 16.9818 4.34775C17.3219 4.40666 17.5498 4.73014 17.4909 5.07025C17.432 5.41036 17.1085 5.63832 16.7684 5.5794C16.7104 5.56936 16.6524 5.55944 16.5943 5.54964L15.7564 16.4417C15.6562 17.7442 14.5701 18.75 13.2638 18.75H6.73641C5.43007 18.75 4.34397 17.7442 4.24377 16.4417L3.40592 5.54964C3.34783 5.55944 3.28978 5.56936 3.23178 5.5794C2.89167 5.63832 2.56819 5.41036 2.50927 5.07025C2.45036 4.73014 2.67831 4.40666 3.01843 4.34775C3.30584 4.29796 3.59429 4.25119 3.88373 4.20748C4.66548 4.08942 5.4545 3.99365 6.2501 3.92081V3.73182C6.2501 2.4281 7.26055 1.31515 8.5963 1.27242C9.06243 1.25751 9.5304 1.25 10.0001 1.25C10.4698 1.25 10.9378 1.25751 11.4039 1.27242C12.7397 1.31515 13.7501 2.4281 13.7501 3.73182ZM8.63627 2.52178C9.08908 2.5073 9.54373 2.5 10.0001 2.5C10.4565 2.5 10.9111 2.5073 11.3639 2.52178C11.9928 2.5419 12.5001 3.06994 12.5001 3.73182V3.82569C11.6731 3.77547 10.8396 3.75 10.0001 3.75C9.16065 3.75 8.32707 3.77547 7.5001 3.82569V3.73182C7.5001 3.06994 8.00742 2.5419 8.63627 2.52178ZM8.3406 7.47598C8.32734 7.13106 8.03697 6.8622 7.69204 6.87546C7.34712 6.88873 7.07826 7.1791 7.09153 7.52402L7.37999 15.024C7.39325 15.3689 7.68362 15.6378 8.02855 15.6245C8.37347 15.6113 8.64233 15.3209 8.62906 14.976L8.3406 7.47598ZM12.9079 7.52402C12.9212 7.1791 12.6523 6.88873 12.3074 6.87546C11.9625 6.8622 11.6721 7.13106 11.6588 7.47598L11.3704 14.976C11.3571 15.3209 11.626 15.6113 11.9709 15.6245C12.3158 15.6378 12.6062 15.3689 12.6194 15.024L12.9079 7.52402Z" fill="#6B7280"/>
</svg>
          </button>
        </div>
      </div>
    `;

    // Append the new row to the form container
    $("#form-country").append(newRow);

    // Reinitialize Select2 for the newly added select elements
    initializeSelect2();

    // Update the visibility of delete buttons
    updateDeleteButtons();
  });

  // Event handler to delete a row when the delete button is clicked
  $(document).on("click", ".delete-row-btn", function () {
    $(this).closest(".row").remove(); // Remove the closest row
    updateDeleteButtons(); // Update the visibility of delete buttons
  });
  // Toggle dropdown visibility on any button click
  $("[data-dropdown-toggle]").on("click", function () {
    const dropdownId = $(this).data("dropdown-toggle");
    $("#" + dropdownId).toggleClass("hidden");
  });

  // Replace button text and hide dropdown when an option is clicked
  $(".dropdown-item").on("click", function () {
    const selectedText = $(this).text().trim(); // Get the text of the clicked option
    const dropdownMenu = $(this).closest("div"); // Get the dropdown menu this item belongs to
    const buttonId = dropdownMenu.siblings("[data-dropdown-toggle]").attr("id"); // Find the associated button using data-dropdown-toggle

    $("#" + buttonId)
      .contents()
      .filter(function () {
        return this.nodeType === 3; // Find the text node in the button
      })
      .first()
      .replaceWith(selectedText); // Replace the text node with the selected option

    // Hide the dropdown after selection
    dropdownMenu.addClass("hidden");
  });

  // Optional: Hide the dropdown if clicked outside
  $(document).on("click", function (e) {
    if (!$(e.target).closest("[data-dropdown-toggle], .dropdown-item").length) {
      $(".dropdown-item").closest("div").addClass("hidden");
    }
  });

  // Initial update to delete button visibility
  updateDeleteButtons();

  dragMedia();
});
