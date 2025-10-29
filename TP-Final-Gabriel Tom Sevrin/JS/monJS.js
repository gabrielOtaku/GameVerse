/*********************************
 ********* Global Script *********
 *********************************/

// --- Variables Globales ---
const CLASS_SHOW = "show";
const CLASS_HIDDEN = "hidden";
const CLASS_EXPANDED = "expanded";
const CLASS_ACTIVE = "active";
const CLASS_DARK_MODE = "dark-mode"; // Assurez-vous d'avoir ce style dans votre CSS

document.addEventListener("DOMContentLoaded", () => {
  // ----------------------------------------------------
  // 1. GESTION DU HEADER (Scroll Effect, Hamburger)
  // ----------------------------------------------------
  const navGaming = document.querySelector(".nav-gaming");
  const hamburgerBtn = document.getElementById("hamburger-btn");
  const mobileMenu = document.getElementById("mobile-menu");
  const navCenterContent = document.getElementById("nav-center-content");

  // Scroll Effect (Point 5 - éviter l'encombrement par le header)
  window.addEventListener("scroll", () => {
    if (navGaming) {
      if (window.scrollY > 50) {
        navGaming.classList.add("scrolled");
      } else {
        navGaming.classList.remove("scrolled");
      }
    }
  });

  // Menu Hamburger (Point 2 - Responsive)
  if (hamburgerBtn && navCenterContent) {
    hamburgerBtn.addEventListener("click", () => {
      navCenterContent.classList.toggle(CLASS_ACTIVE);

      // Toggle de l'icône
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
  // 2. MENU DEROULANT AUTHENTIFICATION (Point 3)
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
  // 3. BARRE DE RECHERCHE DYNAMIQUE (Point 4)
  // ----------------------------------------------------
  const searchBtn = document.getElementById("search-btn");
  const searchContainer = document.querySelector(".search-container");
  const searchInput = document.getElementById("search-input");

  if (searchBtn && searchContainer && searchInput) {
    // Toggle l'expansion de l'input
    searchBtn.addEventListener("click", () => {
      // Si la barre est déjà étendue, on effectue la recherche
      if (searchContainer.classList.contains(CLASS_EXPANDED)) {
        // Vérifie si nous sommes sur la page produit (pour ne pas chercher sur l'accueil)
        if (
          window.location.pathname.includes("produit.php") ||
          window.location.pathname.includes("produit")
        ) {
          performProductSearch();
        } else {
          // Si la barre n'est pas vide, forcer la recherche (ou redirection)
          if (searchInput.value.trim() !== "") {
            // Optionnel: rediriger vers la page produit avec le terme de recherche
            window.location.href = `produit.php?q=${encodeURIComponent(
              searchInput.value.trim()
            )}`;
          } else {
            searchContainer.classList.remove(CLASS_EXPANDED);
          }
        }
      } else {
        // Sinon, on l'étend et on focus l'input
        searchContainer.classList.add(CLASS_EXPANDED);
        searchInput.focus();
      }
    });

    // Gérer la recherche par 'Enter'
    searchInput.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        event.preventDefault();
        // Vérifie si nous sommes sur la page produit pour la recherche en direct
        if (
          window.location.pathname.includes("produit.php") ||
          window.location.pathname.includes("produit")
        ) {
          performProductSearch();
        } else {
          // Sinon, rediriger et passer la requête
          window.location.href = `produit.php?q=${encodeURIComponent(
            searchInput.value.trim()
          )}`;
        }
      }
    });

    // Cacher l'input si l'utilisateur clique ailleurs
    document.addEventListener("click", (event) => {
      if (
        !searchContainer.contains(event.target) &&
        searchContainer.classList.contains(CLASS_EXPANDED) &&
        searchInput.value === "" // Seulement si l'input est vide
      ) {
        searchContainer.classList.remove(CLASS_EXPANDED);
      }
    });

    // Exécuter la recherche si un terme est présent dans l'URL (arrivant d'une autre page)
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get("q");
    if (
      searchQuery &&
      (window.location.pathname.includes("produit.php") ||
        window.location.pathname.includes("produit"))
    ) {
      searchInput.value = searchQuery;
      searchContainer.classList.add(CLASS_EXPANDED);
      performProductSearch();
    }
  }

  // Fonction de recherche (Point 4)
  function performProductSearch() {
    const query = searchInput.value.toLowerCase();
    const productCards = document.querySelectorAll(".product-card");

    if (productCards.length === 0) return; // Quitter si on n'est pas sur la page produit

    if (query === "") {
      // Afficher tous les produits si la recherche est vide
      productCards.forEach((card) => card.classList.remove(CLASS_HIDDEN));
      return;
    }

    let foundMatch = false;

    productCards.forEach((card) => {
      const name =
        card.querySelector(".product-title")?.textContent.toLowerCase() || "";
      const description =
        card.querySelector(".product-description")?.textContent.toLowerCase() ||
        "";

      // Cherche dans le titre OU la description
      if (name.includes(query) || description.includes(query)) {
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

  // ----------------------------------------------------
  // 4. POP-UP DE PARAMETRES (Fermeture/Ouverture)
  // ----------------------------------------------------
  const settingsBtn = document.getElementById("settings-btn");
  const settingsPopupOverlay = document.getElementById(
    "settings-popup-overlay"
  );
  const closePopupBtn = document.getElementById("close-popup-btn");

  const closeSettingsPopup = () => {
    if (settingsPopupOverlay) {
      // Utiliser remove/add pour déclencher la transition CSS
      settingsPopupOverlay.classList.remove(CLASS_SHOW);
      setTimeout(() => {
        settingsPopupOverlay.classList.add(CLASS_HIDDEN);
      }, 300); // 300ms correspond à la transition CSS

      // Cacher le message de succès s'il est affiché
      const successMessage = document.getElementById("success-message");
      if (successMessage) successMessage.classList.remove(CLASS_SHOW);
    }
  };

  if (settingsBtn && settingsPopupOverlay && closePopupBtn) {
    // Assurer que le popup est initialement caché
    settingsPopupOverlay.classList.add(CLASS_HIDDEN);

    // Ouverture (au clic sur le bouton)
    settingsBtn.addEventListener("click", () => {
      settingsPopupOverlay.classList.remove(CLASS_HIDDEN);
      // Petit timeout pour laisser le CSS transitionner le `display` vers `flex`
      setTimeout(() => settingsPopupOverlay.classList.add(CLASS_SHOW), 10);
    });

    // Fermeture (au clic sur le X, overlay, et Échap)
    closePopupBtn.addEventListener("click", closeSettingsPopup);
    settingsPopupOverlay.addEventListener("click", (event) => {
      if (event.target === settingsPopupOverlay) closeSettingsPopup();
    });
    document.addEventListener("keydown", (event) => {
      if (
        event.key === "Escape" &&
        settingsPopupOverlay.classList.contains(CLASS_SHOW)
      ) {
        closeSettingsPopup();
      }
    });
  }

  // -------------------------------------------------------------
  // --- 5. Logique des Switches (Langue/Thème) ---
  // -------------------------------------------------------------

  const langSwitchButtons = document.querySelectorAll(".lang-switch-btn");
  const langSwitchContainer = document.querySelector(".lang-switch-container");
  const themeSwitchButtons = document.querySelectorAll(".theme-switch-btn");
  const themeSwitchContainer = document.querySelector(
    ".theme-switch-container"
  );

  const showSuccessMessage = () => {
    const successMessage = document.getElementById("success-message");
    if (successMessage) {
      successMessage.classList.add(CLASS_SHOW);
      setTimeout(() => successMessage.classList.remove(CLASS_SHOW), 2500);
    }
  };

  const setupSwitchListeners = (buttons, container, storageKey) => {
    if (!container) return;

    buttons.forEach((button) => {
      button.addEventListener("click", () => {
        buttons.forEach((btn) => btn.classList.remove(CLASS_ACTIVE));
        button.classList.add(CLASS_ACTIVE);

        const selectedValue = button.dataset[storageKey];
        // Utilisation de localStorage, car Firestore n'est pas requis pour les préférences locales
        localStorage.setItem(storageKey, selectedValue);

        if (storageKey === "lang") {
          container.classList.toggle("en", selectedValue === "en");
        } else if (storageKey === "theme") {
          container.classList.toggle("dark", selectedValue === "dark");
          document.body.classList.toggle(
            CLASS_DARK_MODE,
            selectedValue === "dark"
          );
          document.body.classList.toggle(
            "bg-dark-bg",
            selectedValue === "dark"
          ); // Règle Tailwind de fallback

          // Gérer la classe CSS qui déclenche l'effet de transition de la pastille
          themeSwitchContainer.classList.toggle(
            "dark",
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
      document.body.classList.toggle("bg-dark-bg", savedTheme === "dark");
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

  // -------------------------------------------------------------
  // --- 6. Logique du Carrousel d'Accueil (Miniatures Dynamiques) ---
  // -------------------------------------------------------------
  const mainCarousel = document.getElementById("carouselExampleIndicators");
  const thumbnails = document.querySelectorAll(".thumbnail-item");

  const updateNeonColor = (activeIndex) => {
    // Récupérer l'élément de la slide active
    // L'index CSS nth-child est 1-basé, d'où `activeIndex + 1`
    const activeSlide = mainCarousel.querySelector(
      `.carousel-item:nth-child(${activeIndex + 1})`
    );

    const neonColor = activeSlide
      ? activeSlide.getAttribute("data-neon-color")
      : "var(--neon-cyan, #00ffcc)";

    thumbnails.forEach((thumb, index) => {
      if (index === activeIndex) {
        // Application de la couleur dynamique pour la bordure et l'ombre
        thumb.style.borderColor = neonColor;
        thumb.style.boxShadow = `0 0 5px ${neonColor}, 0 0 15px ${neonColor}80`;
      } else {
        // Réinitialise les miniatures inactives
        thumb.style.borderColor = "transparent";
        thumb.style.boxShadow = "none";
      }
    });
  };

  if (mainCarousel && thumbnails.length > 0) {
    // Écouteur de l'événement de changement de slide de Bootstrap
    mainCarousel.addEventListener("slide.bs.carousel", function (event) {
      const nextSlideIndex = event.to;

      thumbnails.forEach((thumb, index) => {
        thumb.classList.remove(CLASS_ACTIVE);
        if (index === nextSlideIndex) {
          // Force le re-flow pour relancer l'animation CSS 'scale-up-bounce'
          void thumb.offsetWidth;
          thumb.classList.add(CLASS_ACTIVE);
          updateNeonColor(nextSlideIndex);
        }
      });
    });

    // Gère le clic sur les miniatures
    thumbnails.forEach((thumb, index) => {
      thumb.addEventListener("click", () => {
        updateNeonColor(index);
        // Le changement de slide est géré par l'attribut data-bs-target/data-bs-slide-to
      });
    });

    // --- Initialisation : Applique le style néon au chargement ---
    thumbnails[0].classList.add(CLASS_ACTIVE);
    updateNeonColor(0);
  }

  // -------------------------------------------------------------
  // --- 7. Logique du Catalogue de Produits & MessageBox ---
  // -------------------------------------------------------------

  // Initialisation du Vanilla Tilt pour les cartes produits
  if (typeof VanillaTilt !== "undefined") {
    VanillaTilt.init(document.querySelectorAll(".product-card"));
  }

  // Fonction d'affichage du message personnalisé (pour le panier)
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
      }, 3000); // 3 secondes
    }
  }

  // Fonction d'ajout au panier (globale pour être appelée par onclick)
  window.addToCart = function (buttonElement) {
    const productId = buttonElement.getAttribute("data-product-id");
    const productName = buttonElement.getAttribute("data-product-name");
    const productImage = buttonElement.getAttribute("data-product-image");

    const quantityInput = document.getElementById(`qty-${productId}`);
    const quantity = parseInt(quantityInput?.value) || 1;

    console.log(
      `Ajouté au panier: ${quantity} x ${productName} (Image: ${productImage})`
    );

    // Simulation de l'ajout au panier
    showCustomMessage(productName, productImage, quantity);
  };

  // -------------------------------------------------------------
  // --- 8. GESTION DES TRANSITIONS DE PAGE & PRELOADER (FIX) ---
  // -------------------------------------------------------------
  const preloader = document.getElementById("page-preloader");

  // Écouteur pour tous les liens qui doivent déclencher la transition
  document
    .querySelectorAll(".nav-link-transition, .nav-item a, .auth-menu a")
    .forEach((link) => {
      // Vérifie si le lien mène à une autre page interne
      if (
        link.href &&
        link.hostname === location.hostname &&
        !link.href.includes("#")
      ) {
        link.addEventListener("click", (e) => {
          e.preventDefault(); // Empêche la navigation immédiate

          if (preloader) {
            // Montre le preloader en le réinitialisant
            preloader.classList.remove("loaded");

            // Lance la navigation après un court délai pour que l'utilisateur voie l'écran de chargement
            setTimeout(() => {
              window.location.href = link.href;
            }, 500); // 500 ms de temps de transition
          } else {
            window.location.href = link.href; // Navigation normale si le preloader n'est pas trouvé
          }
        });
      }
    });

  // Fix pour le Preloader (Point 8)
  if (preloader) {
    // Assure que le preloader disparaît après le chargement COMPLET de la fenêtre
    window.onload = function () {
      // Un petit délai pour que l'animation de chargement soit visible
      setTimeout(() => {
        preloader.classList.add("loaded");
      }, 300);
    };
  }

  // -------------------------------------------------------------
  // --- 9. NEXUS AI CHATBOT (Logique Bulle Flottante) ---
  // -------------------------------------------------------------
  const nexusToggleBtn = document.getElementById("nexus-toggle-btn");
  const nexusCloseBtn = document.getElementById("nexus-close-btn");
  const nexusChatbox = document.getElementById("nexus-chatbox");
  const chatAreaBubble = document.getElementById("chat-area-bubble");
  const userInputBubble = document.getElementById("user-input-bubble");
  const sendBtnBubble = document.getElementById("send-btn-bubble");
  const loaderBubble = document.getElementById("loader-bubble");

  const API_KEY = "";
  const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${API_KEY}`;
  const SYSTEM_INSTRUCTION_BASE =
    "You are Nexus AI, a helpful, enthusiastic, and sophisticated virtual assistant for GameVerse, a futurist online gaming store. Your tone is professional, technical, and always helpful. Keep answers concise. If you need up-to-date information, use the provided tools (Google Search). Respond in French.";

  let chatHistory = [];
  let isSending = false;

  // --- Toggle de la Bulle ---
  if (nexusToggleBtn && nexusChatbox && nexusCloseBtn) {
    nexusToggleBtn.addEventListener("click", () => {
      nexusChatbox.classList.toggle(CLASS_SHOW);
      // Masquer le bouton de toggle quand le chatbox est ouvert
      nexusToggleBtn.classList.toggle(CLASS_HIDDEN);

      if (nexusChatbox.classList.contains(CLASS_SHOW)) {
        userInputBubble.focus();
      }
    });

    nexusCloseBtn.addEventListener("click", () => {
      nexusChatbox.classList.remove(CLASS_SHOW);
      nexusToggleBtn.classList.remove(CLASS_HIDDEN);
    });
  }

  // --- Fonction d'affichage des messages ---
  function displayBubbleMessage(text, role, sources = []) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `flex ${
      role === "user" ? "justify-end" : "justify-start"
    }`;

    const bubbleClass = role === "user" ? "user-bubble" : "ai-bubble";

    let messageContent = `
            <div class="chat-bubble ${bubbleClass}">
                <p class="text-xs font-orbitron ${
                  role === "user" ? "text-white/80" : "text-neon-cyan"
                } mb-1">${role === "user" ? "Utilisateur" : "Nexus AI"}</p>
                <p class="text-sm text-gray-200 whitespace-pre-wrap">${text}</p>
        `;

    if (sources.length > 0) {
      const sourceList = sources
        .map(
          (src) =>
            `<li><a href="${src.uri}" target="_blank" class="text-xs text-neon-cyan/80 hover:underline">${src.title}</a></li>`
        )
        .join("");
      messageContent += `
                <div class="mt-2 pt-2 border-t border-white/20 text-xs text-gray-400">
                    <p class="font-bold">Sources:</p>
                    <ul class="list-disc ml-4 mt-1 space-y-1">${sourceList}</ul>
                </div>
            `;
    }

    messageContent += "</div>";
    messageDiv.innerHTML = messageContent;
    chatAreaBubble.appendChild(messageDiv);
    chatAreaBubble.scrollTop = chatAreaBubble.scrollHeight;
  }

  // --- Gestion de l'état d'envoi ---
  function setBubbleSendingState(sending) {
    isSending = sending;
    userInputBubble.disabled = sending;
    sendBtnBubble.disabled = sending;
    if (sending) {
      loaderBubble.classList.remove(CLASS_HIDDEN);
    } else {
      loaderBubble.classList.add(CLASS_HIDDEN);
    }
  }

  // --- Appel de l'API Gemini ---
  async function callGeminiBubble(userQuery) {
    setBubbleSendingState(true);

    chatHistory.push({ role: "user", parts: [{ text: userQuery }] });

    const payload = {
      contents: chatHistory,
      tools: [{ google_search: {} }],
      systemInstruction: {
        parts: [{ text: SYSTEM_INSTRUCTION_BASE }],
      },
    };

    const maxRetries = 5;
    let currentDelay = 1000;

    for (let i = 0; i < maxRetries; i++) {
      try {
        const response = await fetch(API_URL, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });

        if (response.ok) {
          const result = await response.json();
          const candidate = result.candidates?.[0];

          if (candidate && candidate.content?.parts?.[0]?.text) {
            const text = candidate.content.parts[0].text;

            let sources = [];
            const groundingMetadata = candidate.groundingMetadata;
            if (groundingMetadata && groundingMetadata.groundingAttributions) {
              sources = groundingMetadata.groundingAttributions
                .map((attribution) => ({
                  uri: attribution.web?.uri,
                  title: attribution.web?.title,
                }))
                .filter((source) => source.uri && source.title);
            }

            displayBubbleMessage(text, "model", sources);
            chatHistory.push({ role: "model", parts: [{ text: text }] });

            setBubbleSendingState(false);
            return;
          } else {
            throw new Error("Réponse de l'API vide ou mal formée.");
          }
        } else if (response.status === 429 || response.status >= 500) {
          if (i < maxRetries - 1) {
            await new Promise((resolve) => setTimeout(resolve, currentDelay));
            currentDelay *= 2;
          } else {
            throw new Error(
              `Erreur API après ${maxRetries} tentatives. Statut: ${response.status}`
            );
          }
        } else {
          const errorBody = await response.json();
          throw new Error(
            `Erreur HTTP: ${response.status} - ${
              errorBody.error?.message || "Inconnue"
            }`
          );
        }
      } catch (error) {
        console.error("Erreur lors de l'appel Gemini:", error);
        displayBubbleMessage(
          `Désolé, une erreur de communication est survenue. (${error.message})`,
          "model"
        );
        chatHistory.pop();
        setBubbleSendingState(false);
        return;
      }
    }
  }

  // --- Gestion de l'envoi de message ---
  function sendMessageBubbleHandler() {
    if (isSending) return;

    const query = userInputBubble.value.trim();
    if (query === "") return;

    displayBubbleMessage(query, "user");
    userInputBubble.value = "";

    callGeminiBubble(query);
  }

  // --- Événements du Chatbox ---
  if (sendBtnBubble && userInputBubble) {
    sendBtnBubble.addEventListener("click", sendMessageBubbleHandler);

    userInputBubble.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        sendMessageBubbleHandler();
      }
    });
  }

  // -------------------------------------------------------------
  // --- 10. Logique du Footer (Heure en Temps Réel) ---
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
    const dateTimeString = now
      .toLocaleString("fr-CA", options)
      .replace(",", "");
    const datetimeElement = document.getElementById("current-datetime");
    if (datetimeElement) {
      datetimeElement.textContent = dateTimeString;
    }
  }

  updateDateTime();
  setInterval(updateDateTime, 1000);
}); // Fin de document.addEventListener("DOMContentLoaded", ...)

// -------------------------------------------------------------
// --- Logique Social Media (Initialisée hors du DOMContentLoaded pour les librairies) ---
// -------------------------------------------------------------

/**
 * @function initializeSocialLinks
 */
function initializeSocialLinks() {
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
