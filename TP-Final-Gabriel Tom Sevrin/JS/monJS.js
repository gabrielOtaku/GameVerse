/*********************************
 ********* Header Script *********
 *********************************/

// --- Variables Globales ---
const CLASS_SHOW = "show";
const CLASS_HIDDEN = "hidden";
const CLASS_EXPANDED = "expanded";
const CLASS_ACTIVE = "active";
const CLASS_DARK_MODE = "dark-mode";

document.addEventListener("DOMContentLoaded", () => {
  // ----------------------------------------------------
  // 1. GESTION DU HEADER
  // ----------------------------------------------------
  const navGaming = document.querySelector(".nav-gaming");

  const hamburgerBtn = document.getElementById("hamburger-btn");
  const navCenterContent = document.getElementById("nav-center-content");
  const navInnerContainer = document.querySelector(".nav-inner-container");

  // Scroll Effect
  window.addEventListener("scroll", () => {
    if (navGaming) {
      if (window.scrollY > 50) {
        navGaming.classList.add("scrolled");
      } else {
        navGaming.classList.remove("scrolled");
      }
    }
  });

  // Menu Hamburger
  if (hamburgerBtn && navCenterContent) {
    hamburgerBtn.addEventListener("click", () => {
      navCenterContent.classList.toggle(CLASS_ACTIVE);

      const icon = hamburgerBtn.querySelector("i");
      if (navCenterContent.classList.contains(CLASS_ACTIVE)) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    });
  }

  // ----------------------------------------------------
  // 2. MENU DEROULANT AUTHENTIFICATION
  // ----------------------------------------------------
  const authBtn = document.getElementById("auth-btn");
  const authMenu = document.getElementById("auth-menu");
  const authContainer = document.querySelector(".auth-container");

  if (authBtn && authMenu && authContainer) {
    authBtn.addEventListener("click", (event) => {
      event.stopPropagation();
      authMenu.classList.toggle(CLASS_SHOW);
    });

    // Fermer si on clique ailleurs
    document.addEventListener("click", (event) => {
      if (
        !authContainer.contains(event.target) &&
        authMenu.classList.contains(CLASS_SHOW)
      ) {
        authMenu.classList.remove(CLASS_SHOW);
      }
    });
  }

  // ----------------------------------------------------
  // 3. BARRE DE RECHERCHE DYNAMIQUE
  // ----------------------------------------------------
  const searchBtn = document.getElementById("search-btn");
  const searchContainer = document.querySelector(".search-container");
  const searchInput = document.getElementById("search-input");
  // Fonction de recherche de produits
  function performProductSearch() {
    const query = searchInput.value.toLowerCase();
    if (query === "") {
      document
        .querySelectorAll(".product-card")
        .forEach((card) => card.classList.remove(CLASS_HIDDEN));
      return;
    }

    let foundMatch = false;

    document.querySelectorAll(".product-card").forEach((card) => {
      const name =
        card.querySelector(".product-title")?.textContent.toLowerCase() || "";

      if (name.includes(query)) {
        card.classList.remove(CLASS_HIDDEN);
        foundMatch = true;
      } else {
        card.classList.add(CLASS_HIDDEN);
      }
    });

    if (!foundMatch) {
      console.log("Aucun produit trouvé pour la recherche : " + query);
    }
  }

  if (searchBtn && searchContainer && searchInput) {
    searchBtn.addEventListener("click", () => {
      if (searchContainer.classList.contains(CLASS_EXPANDED)) {
        performProductSearch();
      } else {
        searchContainer.classList.add(CLASS_EXPANDED);
        searchInput.focus();
      }
    });

    // Gérer la recherche
    searchInput.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        performProductSearch();
      }
    });

    // Cacher l'input si l'utilisateur clique ailleurs
    document.addEventListener("click", (event) => {
      if (
        !searchContainer.contains(event.target) &&
        searchContainer.classList.contains(CLASS_EXPANDED) &&
        searchInput.value === ""
      ) {
        searchContainer.classList.remove(CLASS_EXPANDED);
      }
    });
  }

  // ----------------------------------------------------
  // 4. POP-UP DE PARAMETRES
  // ----------------------------------------------------
  const settingsBtn = document.getElementById("settings-btn");
  const settingsPopupOverlay = document.getElementById(
    "settings-popup-overlay"
  );
  const closePopupBtn = document.getElementById("close-popup-btn");

  const closeSettingsPopup = () => {
    if (settingsPopupOverlay) {
      settingsPopupOverlay.classList.remove(CLASS_SHOW);
      setTimeout(() => {
        settingsPopupOverlay.style.display = "none";
      }, 300);

      const successMessage = document.getElementById("success-message");
      if (successMessage) successMessage.classList.remove(CLASS_SHOW);
    }
  };

  if (settingsBtn && settingsPopupOverlay && closePopupBtn) {
    settingsBtn.addEventListener("click", () => {
      settingsPopupOverlay.style.display = "flex";
      setTimeout(() => settingsPopupOverlay.classList.add(CLASS_SHOW), 10);
    });

    // Fermeture (au clic sur le X)
    closePopupBtn.addEventListener("click", closeSettingsPopup);

    // Fermeture par clic sur l'overlay
    settingsPopupOverlay.addEventListener("click", (event) => {
      if (event.target === settingsPopupOverlay) closeSettingsPopup();
    });

    // Fermeture par la touche Échap
    document.addEventListener("keydown", (event) => {
      if (
        event.key === "Escape" &&
        settingsPopupOverlay.classList.contains(CLASS_SHOW)
      ) {
        closeSettingsPopup();
      }
    });

    settingsPopupOverlay.style.display = "none";
  }

  //afficher les messages de succès
  const showSuccessMessage = () => {
    const successMessage = document.getElementById("success-message");
    if (successMessage) {
      successMessage.classList.add(CLASS_SHOW);
      setTimeout(() => successMessage.classList.remove(CLASS_SHOW), 2500);
    }
  };

  // -------------------------------------------------------------
  // --- 5. Logique des Switches (Langue/Thème) ---
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
          // Utilisation de la nouvelle variable CLASS_DARK_MODE
          document.body.classList.toggle(
            CLASS_DARK_MODE,
            selectedValue === "dark"
          );
        }
        showSuccessMessage();
      });
    });
  };

  // --- Initialisation des Thèmes et Langues ---
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
// --- 6. Logique du Footer (Date/Heure) ---
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

// -------------------------------------------------------------
// --- 7. Liens Sociaux 3D (VanillaTilt) ---
// -------------------------------------------------------------

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
      VanillaTilt.init(socialLinksContainer.querySelectorAll(".social-icon"), {
        reverse: true,
        max: 25,
        speed: 500,
        glare: true,
        "max-glare": 0.6,
      });
    }
  }
}

initializeSocialLinks();

// -------------------------------------------------------------
// --- 8. Logique du Carrousel d'Accueil ---
// -------------------------------------------------------------
const mainCarousel = document.getElementById("carouselExampleIndicators");
const thumbnails = document.querySelectorAll(".thumbnail-item");

const updateNeonColor = (activeIndex) => {
  const activeSlide = mainCarousel.querySelector(
    `.carousel-item:nth-child(${activeIndex + 1})`
  );

  const neonColor = activeSlide
    ? activeSlide.getAttribute("data-neon-color")
    : "var(--neon-cyan, #00ffcc)";

  thumbnails.forEach((thumb, index) => {
    if (index === activeIndex) {
      thumb.style.borderColor = neonColor;
      thumb.style.boxShadow = `0 0 5px ${neonColor}, 0 0 15px ${neonColor}80`;
    } else {
      thumb.style.borderColor = "transparent";
      thumb.style.boxShadow = "none";
    }
  });
};

if (mainCarousel && thumbnails.length > 0) {
  mainCarousel.addEventListener("slide.bs.carousel", function (event) {
    const nextSlideIndex = event.to;

    thumbnails.forEach((thumb, index) => {
      thumb.classList.remove("active");
      if (index === nextSlideIndex) {
        void thumb.offsetWidth;
        thumb.classList.add("active");
        updateNeonColor(nextSlideIndex);
      }
    });
  });

  thumbnails.forEach((thumb, index) => {
    thumb.addEventListener("click", () => {
      updateNeonColor(index);
    });
  });

  // --- Initialisation : Applique le style néon au chargement ---
  thumbnails[0].classList.add("active");
  updateNeonColor(0);
}

// -------------------------------------------------------------
// --- 9. Logique du Catalogue de Produits ---
// -------------------------------------------------------------

if (typeof VanillaTilt !== "undefined") {
  VanillaTilt.init(document.querySelectorAll(".product-card"));
}

const messageBox = document.getElementById("custom-message-box");
const messageText = document.getElementById("message-text");
const messageImage = document.getElementById("message-image");

if (messageBox && messageText && messageImage) {
  messageBox.classList.remove(CLASS_SHOW);
}

// -------------------------------------------------------------
// --- Fonctions Globales (utilisées par onclick dans le HTML) ---
// -------------------------------------------------------------

//-----Ajout au panier avec message personnalisé -----
function addToCart(buttonElement) {
  const productId = buttonElement.getAttribute("data-product-id");
  const productName = buttonElement.getAttribute("data-product-name");

  const productImage = buttonElement.getAttribute("data-product-image");

  const quantityInput = document.getElementById(`qty-${productId}`);
  const quantity = parseInt(quantityInput.value) || 1;

  console.log(
    `Ajouté au panier: ${quantity} x ${productName} (Image: ${productImage})`
  );

  showCustomMessage(productName, productImage, quantity);
}

function showCustomMessage(productName, productImage, quantity) {
  const messageBox = document.getElementById("custom-message-box");
  const messageText = document.getElementById("message-text");
  const messageImage = document.getElementById("message-image");

  if (messageBox && messageText && messageImage) {
    messageText.textContent = `${quantity}x ${productName}`;
    messageImage.src = productImage;
    messageImage.alt = productName;

    messageBox.classList.add(CLASS_SHOW);

    setTimeout(() => {
      messageBox.classList.remove(CLASS_SHOW);
    }, 3000);
  }
}
