document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("themeToggle");
    const reveal = document.getElementById("themeReveal");
    const body = document.body;

    // Apply stored theme on load
    const isDark = localStorage.getItem("isDarkMode") === "true";
    applyTheme(isDark, false);

    // Toggle on click
    toggleBtn.addEventListener("click", function () {
        const currentlyDark = body.getAttribute("data-bs-theme") === "dark";
        const goingDark = !currentlyDark;

        // Animate reveal with correct color
        reveal.style.backgroundColor = goingDark ? "#121212" : "#ffffff";
        reveal.classList.add("active");

        setTimeout(() => {
            applyTheme(goingDark, true);
            localStorage.setItem("isDarkMode", goingDark);
            reveal.classList.remove("active");
        }, 300); // delay matches half animation duration
    });

    function applyTheme(dark, animateIcon = true) {
        if (dark) {
            body.setAttribute("data-bs-theme", "dark");
            if (animateIcon) toggleBtn.textContent = "🌙";
        } else {
            body.removeAttribute("data-bs-theme");
            if (animateIcon) toggleBtn.textContent = "☀️";
        }
    }
});

//Capitalization of First Words
//First Name in adding patients
document.getElementById("firstName").addEventListener("input", function(e) {
    let value = e.target.value;

    // Capitalize first letter of each word
    value = value.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });

    // Update input value without moving cursor to end
    let start = e.target.selectionStart;
    let end = e.target.selectionEnd;
    e.target.value = value;
    e.target.setSelectionRange(start, end);
});

//Last Name in adding patients
document.getElementById("lastName").addEventListener("input", function(e) {
    let value = e.target.value;

    // Capitalize first letter of each word
    value = value.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });

    // Update input value without moving cursor to end
    let start = e.target.selectionStart;
    let end = e.target.selectionEnd;
    e.target.value = value;
    e.target.setSelectionRange(start, end);
});
