/*********************************
 ********* Header Script *********
 *********************************/

document.addEventListener("DOMContentLoaded", () => {
  // --- Constantes globales ---
  const CLASS_SCROLLED = "scrolled";
  const CLASS_EXPANDED = "expanded";
  const CLASS_SHOW = "show";
  const CLASS_ACTIVE = "active";
  const CLASS_DARK_MODE = "dark-mode";
  const SCROLL_THRESHOLD = 80;

  // --- Message Box (Utilitaires) ---
  const successMessage = document.getElementById("success-message");
  const showSuccessMessage = () => {
    if (successMessage) {
      successMessage.classList.remove(CLASS_SHOW);
      // Assure le redéclenchement de l'animation CSS
      void successMessage.offsetWidth;
      successMessage.classList.add(CLASS_SHOW);

      setTimeout(() => {
        successMessage.classList.remove(CLASS_SHOW);
      }, 3000);
    }
  };

  // -------------------------------------------------------------
  // --- 1. Barre de navigation  ---
  // -------------------------------------------------------------

  // --- Gestion du défilement ---
  const navBar = document.querySelector(".nav-gaming");
  if (navBar) {
    let isScrolled = window.scrollY > SCROLL_THRESHOLD;

    const handleScroll = () => {
      const newIsScrolled = window.scrollY > SCROLL_THRESHOLD;
      if (newIsScrolled !== isScrolled) {
        navBar.classList.toggle(CLASS_SCROLLED, newIsScrolled);
        isScrolled = newIsScrolled;
      }
    };
    window.addEventListener("scroll", handleScroll);
    handleScroll();
  }

  // --- Effet Logo Néon (Optimisé avec requestAnimationFrame) ---
  const logoLink = document.getElementById("dynamic-logo-link");
  const logoImg = document.getElementById("dynamic-logo");
  const neonColor = "0, 255, 204";
  let animationFrameId = null;

  if (logoLink && logoImg) {
    const updateNeonEffect = (e) => {
      if (animationFrameId) window.cancelAnimationFrame(animationFrameId);

      animationFrameId = window.requestAnimationFrame(() => {
        const rect = logoLink.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const distanceX = e.clientX - rect.left - centerX;
        const distanceY = e.clientY - rect.top - centerY;
        const maxDist = Math.hypot(centerX, centerY);

        const intensity = Math.min(
          1,
          Math.hypot(distanceX, distanceY) / maxDist
        );
        const blur = 5 + intensity * 15;
        const opacity = 0.5 + intensity * 0.5;

        logoImg.style.filter = `drop-shadow(0 0 ${blur}px rgba(${neonColor}, ${opacity}))`;
      });
    };

    logoLink.addEventListener("mousemove", updateNeonEffect);
    logoLink.addEventListener("mouseleave", () => {
      if (animationFrameId) window.cancelAnimationFrame(animationFrameId);
      logoImg.style.filter = "drop-shadow(0 0 3px rgba(0, 255, 204, 0.5))";
    });
  }

  // -------------------------------------------------------------
  // --- 2. Barre de recherche  ---
  // -------------------------------------------------------------

  const searchBtn = document.getElementById("search-btn");
  const searchInput = document.getElementById("search-input");
  const searchContainer = searchBtn
    ? searchBtn.closest(".search-container")
    : null;

  if (searchBtn && searchContainer && searchInput) {
    const closeSearch = () => {
      if (searchContainer.classList.contains(CLASS_EXPANDED)) {
        searchContainer.classList.remove(CLASS_EXPANDED);
        searchInput.value = "";
        searchInput.blur();
      }
    };

    searchBtn.addEventListener("click", (event) => {
      event.stopPropagation();
      const isExpanded = searchContainer.classList.contains(CLASS_EXPANDED);

      if (isExpanded) {
        const query = searchInput.value.trim();
        if (query.length > 0) {
          window.location.href = "search.php?q=" + encodeURIComponent(query);
        } else {
          closeSearch();
        }
      } else {
        searchContainer.classList.add(CLASS_EXPANDED);
        searchInput.focus();
      }
    });

    document.addEventListener("click", (event) => {
      if (!searchContainer.contains(event.target)) closeSearch();
    });
    document.addEventListener("keydown", (event) => {
      if (
        event.key === "Escape" &&
        searchContainer.classList.contains(CLASS_EXPANDED)
      )
        closeSearch();
    });
  }

  // -------------------------------------------------------------
  // --- 3. Menu de connexion et Pop-up de paramètres ---
  // -------------------------------------------------------------

  // --- Menu de connexion ---
  const authBtn = document.getElementById("auth-btn");
  const authMenu = document.getElementById("auth-menu");
  const authContainer = document.querySelector(".auth-container");

  if (authBtn && authMenu && authContainer) {
    authBtn.addEventListener("click", (event) => {
      event.stopPropagation();
      authMenu.classList.toggle(CLASS_SHOW);
    });
    document.addEventListener("click", (event) => {
      if (
        !authContainer.contains(event.target) &&
        authMenu.classList.contains(CLASS_SHOW)
      ) {
        authMenu.classList.remove(CLASS_SHOW);
      }
    });
  }

  // --- Pop-up de paramètres ---
  const settingsBtn = document.getElementById("settings-btn");
  const settingsPopupOverlay = document.getElementById(
    "settings-popup-overlay"
  );
  const closePopupBtn = document.getElementById("close-popup-btn");

  const closeSettingsPopup = () => {
    if (
      settingsPopupOverlay &&
      settingsPopupOverlay.classList.contains(CLASS_SHOW)
    ) {
      settingsPopupOverlay.classList.remove(CLASS_SHOW);
      if (successMessage) successMessage.classList.remove(CLASS_SHOW);
    }
  };

  if (settingsBtn && settingsPopupOverlay && closePopupBtn) {
    settingsBtn.addEventListener("click", () => {
      settingsPopupOverlay.classList.add(CLASS_SHOW);
    });
    closePopupBtn.addEventListener("click", closeSettingsPopup);
    settingsPopupOverlay.addEventListener("click", (event) => {
      if (event.target === settingsPopupOverlay) closeSettingsPopup();
    });
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") closeSettingsPopup();
    });
  }

  // -------------------------------------------------------------
  // --- 4. Logique des Switches  ---
  // -------------------------------------------------------------

  const langSwitchButtons = document.querySelectorAll(".lang-switch-btn");
  const langSwitchContainer = document.querySelector(".lang-switch-container");
  const themeSwitchButtons = document.querySelectorAll(".theme-switch-btn");
  const themeSwitchContainer = document.querySelector(
    ".theme-switch-container"
  );

  const setupSwitchListeners = (buttons, container, storageKey) => {
    if (!container) return;

    buttons.forEach((button) => {
      button.addEventListener("click", () => {
        buttons.forEach((btn) => btn.classList.remove(CLASS_ACTIVE));
        button.classList.add(CLASS_ACTIVE);

        const selectedValue = button.dataset[storageKey];
        localStorage.setItem(storageKey, selectedValue);

        if (storageKey === "lang") {
          container.classList.toggle("en", selectedValue === "en");
        } else if (storageKey === "theme") {
          container.classList.toggle("dark", selectedValue === "dark");
          document.body.classList.toggle(
            CLASS_DARK_MODE,
            selectedValue === "dark"
          );
        }
        showSuccessMessage();
      });
    });
  };

  // --- Initialisation ---
  const setInitialState = () => {
    // Thème
    const savedTheme = localStorage.getItem("theme") || "light";
    if (themeSwitchContainer) {
      themeSwitchButtons.forEach((btn) => {
        btn.classList.toggle(CLASS_ACTIVE, btn.dataset.theme === savedTheme);
      });
      document.body.classList.toggle(CLASS_DARK_MODE, savedTheme === "dark");
      themeSwitchContainer.classList.toggle("dark", savedTheme === "dark");
    }
    // Langue
    const savedLang = localStorage.getItem("lang") || "fr";
    if (langSwitchContainer) {
      langSwitchButtons.forEach((btn) => {
        btn.classList.toggle(CLASS_ACTIVE, btn.dataset.lang === savedLang);
      });
      langSwitchContainer.classList.toggle("en", savedLang === "en");
    }
  };

  setupSwitchListeners(langSwitchButtons, langSwitchContainer, "lang");
  setupSwitchListeners(themeSwitchButtons, themeSwitchContainer, "theme");
  setInitialState();
});

// -------------------------------------------------------------
// --- 5. Logique du Footer  ---
// -------------------------------------------------------------

// Fonction pour mettre à jour la date et l'heure en temps réel
function updateDateTime() {
  const now = new Date();
  const options = {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false,
  };
  const dateTimeString = now.toLocaleString("fr-CA", options).replace(",", "");
  const datetimeElement = document.getElementById("current-datetime");
  if (datetimeElement) {
    datetimeElement.textContent = dateTimeString;
  }
}

updateDateTime();
setInterval(updateDateTime, 1000);

/**
 * =======================================================
 * LIENS SOCIAUX 3D
 * =======================================================
 */

document.addEventListener("DOMContentLoaded", () => {
  const socialData = [
    {
      href: "https://www.facebook.com/GameOne",
      class: "fab fa-facebook-f",
      name: "facebook",
      "data-tilt-glare": "true",
      "data-tilt-max": "30",
      "data-tilt-speed": "400",
    },
    {
      href: "https://www.twitter.com/GameVerse",
      class: "fab fa-twitter",
      name: "twitter",
      "data-tilt-glare": "true",
      "data-tilt-max": "30",
      "data-tilt-speed": "400",
    },
    {
      href: "https://www.instagram.com/GameVerse",
      class: "fab fa-instagram",
      name: "instagram",
      "data-tilt-glare": "true",
      "data-tilt-max": "30",
      "data-tilt-speed": "400",
    },
    {
      href: "https://www.linkedin.com/company/GameVerse",
      class: "fab fa-linkedin-in",
      name: "linkedin",
      "data-tilt-glare": "true",
      "data-tilt-max": "30",
      "data-tilt-speed": "400",
    },
  ];

  /**
   * @function initializeSocialLinks
   */
  function initializeSocialLinks() {
    const socialLinksContainer = document.querySelector(
      ".social-links-container"
    );

    if (socialLinksContainer) {
      socialData.forEach((item) => {
        const link = document.createElement("a");

        link.href = item.href;
        link.target = "_blank";

        // Ajouter les classes pour le style et Font Awesome.
        link.classList.add("social-icon", item.name);
        link.innerHTML = `<i class="${item.class}"></i>`;

        // l'effet 3D
        link.setAttribute("data-tilt", "true");

        for (const key in item) {
          if (key.startsWith("data-tilt-")) {
            link.setAttribute(key, item[key]);
          }
        }

        socialLinksContainer.appendChild(link);
      });

      if (
        typeof VanillaTilt !== "undefined" &&
        socialLinksContainer.children.length > 0
      ) {
        VanillaTilt.init(
          socialLinksContainer.querySelectorAll(".social-icon"),
          {
            reverse: true,
            max: 25,
            speed: 500,
            glare: true,
            "max-glare": 0.6,
          }
        );
      }
    }
  }

  initializeSocialLinks();
});

// -------------------------------------------------------------
// --- 6. Logique du Catalogue de Produits  ---
// -------------------------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
  if (typeof VanillaTilt !== "undefined") {
    VanillaTilt.init(document.querySelectorAll(".product-card"));
  }

  const messageBox = document.getElementById("custom-message-box");
  const messageText = document.getElementById("message-text");

  window.showMessage = (message) => {
    if (messageBox && messageText) {
      messageText.textContent = message;
      messageBox.classList.add("show");

      setTimeout(() => {
        messageBox.classList.remove("show");
      }, 3000);
    } else {
      console.warn(
        "Message box not found, displaying message in console: " + message
      );
    }
  };
});

function addToCart(buttonElement) {
  const productId = buttonElement.dataset.productId;
  const productName = buttonElement.dataset.productName;
  const quantityInput = document.getElementById(`qty-${productId}`);

  // Vérification de l'existence des éléments
  if (!quantityInput) {
    window.showMessage("❌ Erreur: Champ quantité introuvable.");
    return;
  }

  const quantity = parseInt(quantityInput.value, 10);

  if (quantity > 0) {
    console.log(
      `Produit ajouté au panier : ID ${productId}, Nom: ${productName}, Quantité: ${quantity}`
    );
    window.showMessage(`✅ ${quantity} x ${productName} ajouté(s) au panier !`);
    quantityInput.value = 1;
  } else {
    window.showMessage("❌ Veuillez sélectionner une quantité valide.");
  }
}
