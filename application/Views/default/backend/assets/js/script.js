$(document).ready(function () {
  const toggleDropDown = function () {
    $("#btnFilter").click(function () {
      $("#dropdownFilter").toggleClass("hidden");
    });
    $("#btnColumns").click(function () {
      $("#dropdownColumns").toggleClass("hidden");
    });
    // Enable sortable functionality
    $("#sortable").sortable({
      items: "> li",
      handle: ".drag-icon", // Use the drag-icon class as the handle
      placeholder: "bg-gray-200 h-8",
    });

    $("#sortable").disableSelection(); // Prevent text selection while dragging
  };
  const toggleSwitch = function () {
    $(".toggle-checkbox").change(function () {
      const parentLabel = $(this).closest("label");

      if ($(this).is(":checked")) {
        // Change background to green and update status to "On"
        parentLabel
          .find(".slider")
          .removeClass("bg-gray-200")
          .addClass("bg-blue-600");
        parentLabel
          .find(".status-text")
          .text("On")
          .addClass("text-blue-600")
          .removeClass("text-gray-500");

        // Move the dot to the right
        parentLabel.find(".dot").addClass("translate-x-6");
      } else {
        // Change background to grey and update status to "Off"
        parentLabel
          .find(".slider")
          .removeClass("bg-blue-600")
          .addClass("bg-gray-200");
        parentLabel
          .find(".status-text")
          .text("Off")
          .addClass("text-gray-500")
          .removeClass("text-blue-600");

        // Move the dot to the left
        parentLabel.find(".dot").removeClass("translate-x-6");
      }
    });
  };
  const toggleMenu = function () {
    $(".bugger-menu").click(function () {
      $(".sidebar").toggleClass("active");
    });
  };

  // Initialize function
  toggleDropDown();
  toggleSwitch();
  toggleMenu();
});
