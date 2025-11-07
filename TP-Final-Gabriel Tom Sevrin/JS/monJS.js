// TP-Final-Gabriel Tom Sevrin/JS/monJS.js

/*********************************
 ********* Global Script *********
 *********************************/

// --- Variables Globales ---
const CLASS_SHOW = "show";
const CLASS_HIDDEN = "hidden";
const CLASS_EXPANDED = "expanded";
const CLASS_ACTIVE = "active";
const CLASS_DARK_MODE = "dark-mode";
const CLASS_LIGHT_MODE = "light-mode";

// --- Fonctions globales d'aide ---

/**
 * Affiche un popup avec la classe 'show' puis le cache.
 * @param {HTMLElement} popupElement
 * @param {number} duration
 */
const showGlobalPopup = (popupElement, duration = 4000) => {
  if (popupElement) {
    // S'assurer qu'il est visible au début (si display:none est appliqué)
    popupElement.style.display = "flex";
    // Ajouter la classe pour l'animation
    popupElement.classList.add(CLASS_SHOW);

    setTimeout(() => {
      popupElement.classList.remove(CLASS_SHOW);
      // Cacher complètement après l'animation
      setTimeout(() => {
        popupElement.style.display = "none";
      }, 300); // 300ms pour correspondre à une transition standard
    }, duration);
  }
};

/**
 * Ouvre le client de messagerie pour envoyer un email.
 */
const openMailClient = (email, subject, body) => {
  const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(
    subject
  )}&body=${encodeURIComponent(body)}`;
  window.location.href = mailtoLink;
};

document.addEventListener("DOMContentLoaded", () => {
  // ----------------------------------------------------
  // 1. GESTION DU HEADER (Scroll Effect, Hamburger)
  // ----------------------------------------------------
  const navGaming = document.querySelector(".nav-gaming");
  const hamburgerBtn = document.getElementById("hamburger-btn");
  const navCenterContent = document.getElementById("nav-center-content");

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
  // 2. MENU DEROULANT AUTHENTIFICATION (FIXED)
  // ----------------------------------------------------
  const authBtn = document.getElementById("auth-btn");
  const authMenu = document.getElementById("auth-menu");
  const authContainer = document.querySelector(".auth-container");

  if (authBtn && authMenu && authContainer) {
    // 1. GESTION DU CLIC (pour ouvrir/fermer le menu)
    authBtn.addEventListener("click", (event) => {
      event.stopPropagation();
      // On utilise CLASS_ACTIVE qui est géré dans le CSS pour l'affichage
      authBtn.classList.toggle(CLASS_ACTIVE);
      // Ajouter/retirer la classe 'show' sur le menu (si votre CSS l'utilise)
      authMenu.classList.toggle(CLASS_SHOW);
    });

    // 2. FERMETURE SI ON CLIQUE AILLEURS
    document.addEventListener("click", (event) => {
      // Vérifie si l'élément cliqué N'EST PAS dans le conteneur auth ET si le bouton est actif
      if (
        !authContainer.contains(event.target) &&
        authBtn.classList.contains(CLASS_ACTIVE)
      ) {
        authBtn.classList.remove(CLASS_ACTIVE);
        authMenu.classList.remove(CLASS_SHOW);
      }
    });
  }

  // ----------------------------------------------------
  // 3. BARRE DE RECHERCHE DYNAMIQUE (FIXED)
  // ----------------------------------------------------
  const searchBtn = document.getElementById("search-btn");
  const searchContainer = document.querySelector(".search-container");
  const searchInput = document.getElementById("search-input");

  const performSearchAndRedirect = (query) => {
    // MODIFIÉ: S'assurer que le chemin est correct pour la redirection
    const path = window.location.pathname.includes("/PHP/")
      ? "produit.php"
      : "PHP/produit.php";
    window.location.href = `${path}?q=${encodeURIComponent(query.trim())}`;
  };

  if (searchBtn && searchContainer && searchInput) {
    searchBtn.addEventListener("click", (event) => {
      event.stopPropagation();

      if (searchContainer.classList.contains(CLASS_EXPANDED)) {
        // Si l'input N'EST PAS vide, on effectue la recherche ou la redirection
        if (searchInput.value.trim() !== "") {
          if (
            window.location.pathname.includes("produit.php") ||
            window.location.pathname.includes("produit")
          ) {
            if (typeof performProductSearch === "function") {
              performProductSearch();
            } else {
              performSearchAndRedirect(searchInput.value);
            }
          } else {
            performSearchAndRedirect(searchInput.value);
          }
        } else {
          // Si l'input est vide, on la réduit
          searchContainer.classList.remove(CLASS_EXPANDED);
        }
      } else {
        // Sinon, on l'étend et on focus l'input
        searchContainer.classList.add(CLASS_EXPANDED);
        searchInput.focus();
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

    // Gérer la recherche par 'Enter'
    searchInput.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        event.preventDefault();
        if (searchInput.value.trim() === "") return;

        if (
          window.location.pathname.includes("produit.php") ||
          window.location.pathname.includes("produit")
        ) {
          if (typeof performProductSearch === "function") {
            performProductSearch();
          }
        } else {
          performSearchAndRedirect(searchInput.value);
        }
      }
    });

    // Exécuter la recherche si un terme est présent dans l'URL (sur produit.php)
    const urlParams = new URLSearchParams(window.location.search);
    const urlSearchQuery = urlParams.get("q");
    if (
      urlSearchQuery &&
      (window.location.pathname.includes("produit.php") ||
        window.location.pathname.includes("produit"))
    ) {
      searchInput.value = urlSearchQuery;
      searchContainer.classList.add(CLASS_EXPANDED);
      // Exécuter la recherche immédiatement si la fonction est définie
      if (typeof performProductSearch === "function") {
        // Délai nécessaire pour s'assurer que le DOM est prêt, même après DOMContentLoaded
        setTimeout(performProductSearch, 100);
      }
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
      settingsPopupOverlay.classList.remove(CLASS_SHOW);
      setTimeout(() => {
        settingsPopupOverlay.classList.add(CLASS_HIDDEN);
      }, 300);

      const successMessage = document.getElementById("success-message");
      if (successMessage) successMessage.classList.remove(CLASS_SHOW);
    }
  };

  if (settingsBtn && settingsPopupOverlay && closePopupBtn) {
    settingsPopupOverlay.classList.add(CLASS_HIDDEN);

    settingsBtn.addEventListener("click", () => {
      settingsPopupOverlay.classList.remove(CLASS_HIDDEN);
      setTimeout(() => settingsPopupOverlay.classList.add(CLASS_SHOW), 10);
    });

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
  // --- 5. Logique des Switches (Langue/Thème) (FIXED) ---
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
      successMessage.innerHTML = "🎉 Configuration enregistrée !";
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
        localStorage.setItem(storageKey, selectedValue);

        if (storageKey === "lang") {
          container.classList.toggle("en", selectedValue === "en");
          container.classList.toggle("fr", selectedValue === "fr");
        } else if (storageKey === "theme") {
          const isDark = selectedValue === "dark";
          container.classList.toggle("dark", isDark);
          container.classList.toggle("light", !isDark);

          document.body.classList.toggle(CLASS_DARK_MODE, isDark);
          document.body.classList.toggle(CLASS_LIGHT_MODE, !isDark);
          document.body.classList.toggle("bg-dark-bg", isDark);
          document.body.classList.toggle("bg-light-bg", !isDark);

          themeSwitchContainer.classList.toggle("dark", isDark);
        }
        showSuccessMessage();
      });
    });
  };

  // --- Initialisation ---
  const setInitialState = () => {
    // Thème
    const savedTheme = localStorage.getItem("theme") || "dark";
    if (themeSwitchContainer) {
      themeSwitchButtons.forEach((btn) => {
        btn.classList.toggle(CLASS_ACTIVE, btn.dataset.theme === savedTheme);
      });
      const isDark = savedTheme === "dark";
      document.body.classList.toggle(CLASS_DARK_MODE, isDark);
      document.body.classList.toggle(CLASS_LIGHT_MODE, !isDark);
      document.body.classList.toggle("bg-dark-bg", isDark);
      document.body.classList.toggle("bg-light-bg", !isDark);
      themeSwitchContainer.classList.toggle("dark", isDark);
    }
    // Langue
    const savedLang = localStorage.getItem("lang") || "fr";
    if (langSwitchContainer) {
      langSwitchButtons.forEach((btn) => {
        btn.classList.toggle(CLASS_ACTIVE, btn.dataset.lang === savedLang);
      });
      langSwitchContainer.classList.toggle("en", savedLang === "en");
      langSwitchContainer.classList.toggle("fr", savedLang === "fr");
    }
  };

  setupSwitchListeners(langSwitchButtons, langSwitchContainer, "lang");
  setupSwitchListeners(themeSwitchButtons, themeSwitchContainer, "theme");
  setInitialState();

  // -------------------------------------------------------------
  // --- 6. Logique du Carrousel d'Accueil (Miniatures Dynamiques) (FIXED) ---
  // -------------------------------------------------------------
  const mainCarousel = document.getElementById("carouselExampleIndicators");
  const thumbnails = document.querySelectorAll(".thumbnail-item");

  const updateNeonColor = (activeIndex) => {
    // Correction pour utiliser le carrousel Bootstrap de la page Accueil.php
    const activeSlide = mainCarousel?.querySelector(
      `.carousel-item:nth-child(${activeIndex + 1})`
    );

    if (!activeSlide) return;

    // Récupérer la couleur directement de l'attribut data-neon-color du slide
    const neonColor = activeSlide.getAttribute("data-neon-color") || "#00ffcc"; // Fallback: neon-cyan

    thumbnails.forEach((thumb, index) => {
      if (index === activeIndex) {
        thumb.style.borderColor = neonColor;
        thumb.style.boxShadow = `0 0 5px ${neonColor}, 0 0 15px ${neonColor}80`;
      } else {
        thumb.style.borderColor = "transparent";
        thumb.style.boxShadow = "none";
      }
      thumb.classList.toggle(CLASS_ACTIVE, index === activeIndex);
    });
  };

  if (mainCarousel && thumbnails.length > 0) {
    // Événement pour écouter le changement de slide Bootstrap
    const carouselInstance = bootstrap.Carousel.getInstance(mainCarousel);

    if (carouselInstance) {
      mainCarousel.addEventListener("slide.bs.carousel", function (event) {
        const nextSlideIndex = event.to;
        updateNeonColor(nextSlideIndex);
      });
    }

    // 2. Gestion du clic sur les miniatures
    thumbnails.forEach((thumb, index) => {
      thumb.addEventListener("click", () => {
        // Déclenche la transition Bootstrap
        if (typeof bootstrap !== "undefined" && bootstrap.Carousel) {
          const carousel = bootstrap.Carousel.getInstance(mainCarousel);
          if (carousel) {
            carousel.to(index);
          }
        }
        // Le slide.bs.carousel se chargera d'appeler updateNeonColor(index);
      });
    });

    // --- Initialisation ---
    const initialActiveIndex = 0;
    if (thumbnails[initialActiveIndex]) {
      updateNeonColor(initialActiveIndex);
    }
  }

  // -------------------------------------------------------------
  // --- 7. Logique du Catalogue de Produits & MessageBox ---
  // -------------------------------------------------------------

  if (typeof VanillaTilt !== "undefined") {
    VanillaTilt.init(document.querySelectorAll(".product-card"));
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
      }, 3000); // 3 secondes
    }
  }

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
  // --- 8. GESTION DES TRANSITIONS DE PAGE & PRELOADER ---
  // -------------------------------------------------------------
  const preloader = document.getElementById("page-preloader");

  document
    .querySelectorAll(".nav-link-transition, .nav-item a, .auth-menu a")
    .forEach((link) => {
      if (
        link.href &&
        link.hostname === location.hostname &&
        !link.href.includes("#")
      ) {
        link.addEventListener("click", (e) => {
          e.preventDefault();

          if (preloader) {
            preloader.classList.remove("loaded");

            setTimeout(() => {
              window.location.href = link.href;
            }, 500);
          } else {
            window.location.href = link.href;
          }
        });
      }
    });

  if (preloader) {
    window.onload = function () {
      setTimeout(() => {
        preloader.classList.add("loaded");
      }, 300);
    };
  }

  // -------------------------------------------------------------
  // --- 9. NEXUS AI CHATBOT (Logique Bulle Flottante) (FIXED) ---
  // -------------------------------------------------------------
  const nexusToggleBtn = document.getElementById("nexus-toggle-btn");
  const nexusCloseBtn = document.getElementById("nexus-close-btn");
  const nexusChatbox = document.getElementById("nexus-chatbox");
  const chatAreaBubble = document.getElementById("chat-area-bubble");
  const userInputBubble = document.getElementById("user-input-bubble");
  const sendBtnBubble = document.getElementById("send-btn-bubble");
  const loaderBubble = document.getElementById("loader-bubble");

  const API_KEY = "AIzaSyBsS1vz8HHyS87C3XElz2gcaAygSJ27y0k";
  const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${API_KEY}`;
  const SYSTEM_INSTRUCTION_BASE =
    "You are Nexus AI, a helpful, enthusiastic, and sophisticated virtual assistant for GameVerse, a futurist online gaming store. Your tone is professional, technical, and always helpful. Keep answers concise. If you need up-to-date information, use the provided tools (Google Search). Respond in French.";
  let chatHistory = [];
  let isSending = false;

  // --- Toggle de la Bulle (FIXED) ---
  if (nexusToggleBtn && nexusChatbox && nexusCloseBtn) {
    // S'assurer que le bouton est visible au chargement
    nexusToggleBtn.classList.remove(CLASS_HIDDEN);
    nexusChatbox.classList.remove(CLASS_SHOW);

    // Si le CSS cache la chatbox par défaut, le JS prend le relais
    // Sinon, on laisse le CSS cacher par défaut et on utilise toggle

    nexusToggleBtn.addEventListener("click", () => {
      // Simplification de la logique de toggle (si CLASS_SHOW est utilisée dans le CSS)
      nexusChatbox.classList.toggle(CLASS_SHOW);
      nexusToggleBtn.classList.toggle(CLASS_HIDDEN);

      if (nexusChatbox.classList.contains(CLASS_SHOW) && userInputBubble) {
        userInputBubble.focus();
        if (chatAreaBubble) {
          chatAreaBubble.scrollTop = chatAreaBubble.scrollHeight;
        }
      }
    });

    nexusCloseBtn.addEventListener("click", () => {
      nexusChatbox.classList.remove(CLASS_SHOW);
      nexusToggleBtn.classList.remove(CLASS_HIDDEN);
    });
  }

  // --- Fonction d'affichage des messages (CORRECTED) ---
  function displayBubbleMessage(text, role, sources = []) {
    if (!chatAreaBubble) return;

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

    // Ajout des sources (si elles existent)
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

    messageContent += "</div>"; // Fermeture unique du chat-bubble
    messageDiv.innerHTML = messageContent;

    chatAreaBubble.appendChild(messageDiv);
    chatAreaBubble.scrollTop = chatAreaBubble.scrollHeight;
  }

  // --- Gestion de l'état d'envoi (SIMPLIFIED) ---
  function setBubbleSendingState(sending) {
    isSending = sending;
    if (userInputBubble) userInputBubble.disabled = sending;
    if (sendBtnBubble) sendBtnBubble.disabled = sending;

    if (loaderBubble) {
      loaderBubble.classList.toggle(CLASS_HIDDEN, !sending);
    }
    if (nexusToggleBtn) {
      // Cache le bouton de toggle si le chatbox est en cours d'envoi
      nexusToggleBtn.classList.toggle(CLASS_HIDDEN, sending);
    }
  }

  // --- Appel de l'API Gemini (SIMPLIFIED) ---
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
            const errorInfo = await response.text();
            throw new Error(
              `Réponse de l'API vide ou mal formée. Info: ${errorInfo.substring(
                0,
                50
              )}...`
            );
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

    const query = userInputBubble?.value?.trim() ?? "";
    if (query === "") return;

    displayBubbleMessage(query, "user");
    if (userInputBubble) userInputBubble.value = "";

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

  // -------------------------------------------------------------
  // --- 11. Logique du Formulaire de Contact & Popups (FIXED) ---
  // -------------------------------------------------------------
  const contactForm = document.getElementById("contact-form");
  const contactSuccessPopup = document.getElementById("contact-success-popup");
  const contactErrorPopup = document.getElementById("contact-error-popup");

  if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault();

      const formData = new FormData(contactForm);
      const name = formData.get("name") || "Client GameVerse";
      const email = formData.get("email");
      const subject = formData.get("subject");
      const message = formData.get("message");

      const destinationEmail = "gabrielherve94250@gmail.com";
      showGlobalPopup(contactSuccessPopup);

      const mailBody = `Nom: ${name}\nEmail: ${email}\n\nMessage:\n${message}`;
      openMailClient(destinationEmail, subject, mailBody);

      contactForm.reset();
    });
  }

  // -------------------------------------------------------------
  // --- 12. Logique du Formulaire d'inscription (FIXED) ---
  // -------------------------------------------------------------
  const registrationForm = document.getElementById("registration-form");

  // Noms de variables spécifiques pour la page Inscription
  const regSuccessPopup = document.getElementById("popup-success");
  const regErrorPopup = document.getElementById("popup-error");
  const regErrorDetails = document.getElementById("error-details");
  const regSuccessCloseBtn = document.getElementById("success-close-btn");
  const regErrorCloseBtn = document.getElementById("error-close-btn");
  const suggestPasswordBtn = document.getElementById("suggest-password-btn");
  const botModel = document.getElementById("bot-model");
  // AJOUTÉ: Element d'affichage du mot de passe
  const passwordSuggestionDisplay = document.getElementById(
    "password-suggestion-display"
  );

  // --- Noms d'animations (Doivent correspondre à votre fichier .glb) ---
  const ANIM_GREET = "Salutation";
  const ANIM_HAPPY = "Content";
  const ANIM_ERROR = "CourtCircuit";
  const ANIM_RECOVER = "Réveil";

  // --- Fonction pour jouer une animation ---
  const playBotAnimation = (name, loop = false) => {
    if (botModel) {
      botModel.animationName = name;
      botModel.setAttribute("autoplay", true);
      if (!loop) {
        botModel.addEventListener(
          "finished",
          () => {
            botModel.removeAttribute("autoplay");
          },
          { once: true }
        );
      }
    }
  };

  if (registrationForm) {
    // --- Initialisation du Bot (Salutation) ---
    if (botModel) {
      botModel.addEventListener("load", () => {
        playBotAnimation(ANIM_GREET);
      });
    }

    // --- Suggestion de Mot de Passe ---
    if (suggestPasswordBtn) {
      suggestPasswordBtn.addEventListener("click", () => {
        const passwordInput = document.getElementById("mot_de_passe");
        const charset =
          "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let password = "";
        // Limiter la suggestion à 12 caractères pour rester pratique
        for (let i = 0; i < 12; i++) {
          password += charset.charAt(
            Math.floor(Math.random() * charset.length)
          );
        }
        if (passwordInput) {
          passwordInput.value = password;
        }

        if (regErrorPopup) regErrorPopup.classList.remove(CLASS_SHOW);
        if (regSuccessPopup) regSuccessPopup.classList.remove(CLASS_SHOW);
        if (regErrorDetails) regErrorDetails.innerHTML = "";

        // Affichage du mot de passe suggéré
        if (passwordSuggestionDisplay) {
          passwordSuggestionDisplay.innerHTML = `Mot de passe suggéré: <strong>${password}</strong> (Cliquez pour copier)`;
          // Rendre visible la div de suggestion
          passwordSuggestionDisplay.classList.remove(CLASS_HIDDEN);
          passwordSuggestionDisplay.classList.remove("hidden");

          // Logique de copie au clic (Feature 2)
          passwordSuggestionDisplay.onclick = () => {
            navigator.clipboard.writeText(password).then(() => {
              const originalText = passwordSuggestionDisplay.innerHTML;
              passwordSuggestionDisplay.innerHTML =
                "✅ Copié dans le presse-papiers !";
              setTimeout(() => {
                passwordSuggestionDisplay.innerHTML = originalText;
              }, 1500);
            });
          };
        }

        // Affichage de la suggestion
        playBotAnimation(ANIM_HAPPY);

        // Simuler un message de succès pour la suggestion de mot de passe
        const successMessage = document.getElementById("success-message");
        if (successMessage) {
          successMessage.innerHTML = "Mot de passe suggéré généré !";
          showGlobalPopup(successMessage, 3000);
        }
      });
    }

    // --- Gestion des Popups et du Bot ---
    const showRegPopup = (popup, anim) => {
      if (popup) popup.classList.add("show");
      playBotAnimation(anim);
    };

    const hideRegPopup = (popup, redirect = false) => {
      if (popup) popup.classList.remove("show");
      if (redirect) {
        // CORRECTION: Assurer la bonne redirection
        const redirectPath = window.location.pathname.includes("/PHP/")
          ? "seConnecter.php"
          : "PHP/seConnecter.php";
        window.location.href = redirectPath;
      }
    };

    if (regErrorCloseBtn) {
      regErrorCloseBtn.addEventListener("click", () => {
        hideRegPopup(regErrorPopup);
        playBotAnimation(ANIM_RECOVER); // Le robot se relève
      });
    }

    if (regSuccessCloseBtn) {
      regSuccessCloseBtn.addEventListener("click", () => {
        hideRegPopup(regSuccessPopup, true); // Redirection
      });
    }

    // --- Validation et Soumission du Formulaire ---
    registrationForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      document
        .querySelectorAll(".input-field")
        .forEach((el) => el.classList.remove("error"));
      if (regErrorDetails) regErrorDetails.innerHTML = "";

      const formData = new FormData(registrationForm);
      let clientErrors = {};

      // Validation côté client (similaire à PHP)
      const email = formData.get("courriel");
      const password = formData.get("mot_de_passe");
      const dob = formData.get("date_naissance");

      if (!email || !email.includes("@"))
        clientErrors["courriel"] = "Format de courriel invalide.";
      // MODIFIÉ: Retrait du minimum de 8 caractères. Max 64 maintenu.
      if (!password)
        clientErrors["mot_de_passe"] = "Le mot de passe est requis.";
      else if (password.length > 64)
        clientErrors["mot_de_passe"] = "Mot de passe: Maximum 64 caractères.";

      if (!formData.get("prenom")?.trim())
        clientErrors["prenom"] = "Le prénom est requis.";
      if (!formData.get("nom")?.trim())
        clientErrors["nom"] = "Le nom est requis.";

      if (dob) {
        const dobDate = new Date(dob);
        const minDate = new Date();
        minDate.setFullYear(minDate.getFullYear() - 13);
        if (dobDate > minDate)
          clientErrors["date_naissance"] = "Âge minimum de 13 ans requis.";
      } else {
        clientErrors["date_naissance"] = "La date de naissance est requise.";
      }

      // Afficher les erreurs côté client
      if (Object.keys(clientErrors).length > 0) {
        let htmlErrors = '<p class="font-bold mb-2">Problèmes de format:</p>';
        for (const field in clientErrors) {
          const inputField = document
            .querySelector(`[name="${field}"]`)
            ?.closest(".input-field");
          if (inputField) inputField.classList.add("error");
          htmlErrors += `<p class="text-sm">- ${clientErrors[field]}</p>`;
        }
        if (regErrorDetails) regErrorDetails.innerHTML = htmlErrors;
        showRegPopup(regErrorPopup, ANIM_ERROR);
        return;
      }

      // Soumission à PHP via Fetch (AJAX)
      try {
        const response = await fetch("Inscription.php", {
          method: "POST",
          body: formData,
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
        });

        const result = await response.json();

        if (result.success) {
          showRegPopup(regSuccessPopup, ANIM_HAPPY);
        } else {
          let htmlErrors =
            '<p class="font-bold mb-2">Problèmes rencontrés :</p>';
          const allErrors = { ...result.errors };

          if (allErrors.server || allErrors.general) {
            htmlErrors += `<p class="text-sm">- ${
              allErrors.server || allErrors.general
            }</p>`;
          } else {
            for (const field in allErrors) {
              const inputField = document
                .querySelector(`[name="${field}"]`)
                ?.closest(".input-field");
              if (inputField) inputField.classList.add("error");
              htmlErrors += `<p class="text-sm">- ${allErrors[field]}</p>`;
            }
          }

          if (regErrorDetails) regErrorDetails.innerHTML = htmlErrors;
          showRegPopup(regErrorPopup, ANIM_ERROR);
        }
      } catch (error) {
        console.error("Erreur de soumission:", error);
        if (regErrorDetails)
          regErrorDetails.innerHTML = `<p class="font-bold mb-2">Erreur Inconnue:</p><p class="text-sm">Impossible de communiquer avec le serveur.</p>`;
        showRegPopup(regErrorPopup, ANIM_ERROR);
      }
    });
  }

  // -------------------------------------------------------------
  // --- 13. Logique du Formulaire de Connexion (seConnecter.php) ---
  // -------------------------------------------------------------
  const loginForm = document.getElementById("login-form");

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(loginForm);

      try {
        const response = await fetch("seConnecter.php", {
          method: "POST",
          body: formData,
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
        });

        const result = await response.json();
        const loginSuccessPopup = document.getElementById("popup-success");
        const loginErrorPopup = document.getElementById("popup-error");
        const errorDetails = document.getElementById("error-details");
        const successCloseBtn = document.getElementById("success-close-btn");
        const errorCloseBtn = document.getElementById("error-close-btn");
        const successMessageElement =
          document.getElementById("success-message");

        // Fermer les anciens popups
        if (loginSuccessPopup) loginSuccessPopup.classList.remove(CLASS_SHOW);
        if (loginErrorPopup) loginErrorPopup.classList.remove(CLASS_SHOW);

        if (result.success) {
          // Connexion réussie, affiche le popup de succès et redirige
          if (successMessageElement)
            successMessageElement.textContent = result.message;
          if (loginSuccessPopup) showGlobalPopup(loginSuccessPopup, 2000);

          setTimeout(() => {
            window.location.href = result.redirect; // Redirection vers Accueil.php?login=success
          }, 2000);
        } else {
          // Échec de la connexion
          let htmlErrors =
            '<p class="font-bold mb-2">Détails de l\'erreur :</p>';
          const allErrors = { ...result.errors };

          for (const field in allErrors) {
            const inputField = document
              .querySelector(`[name="${field}"]`)
              ?.closest(".input-field");
            if (inputField) inputField.classList.add("error");
            htmlErrors += `<p class="text-sm">- ${allErrors[field]}</p>`;
          }

          if (errorDetails) errorDetails.innerHTML = htmlErrors;
          if (loginErrorPopup) loginErrorPopup.classList.add(CLASS_SHOW);

          // Assurer la fermeture du popup d'erreur
          if (errorCloseBtn) {
            errorCloseBtn.onclick = () =>
              loginErrorPopup.classList.remove(CLASS_SHOW);
          }
        }
      } catch (error) {
        console.error("Erreur de soumission:", error);
        const loginErrorPopup = document.getElementById("popup-error");
        const errorDetails = document.getElementById("error-details");
        if (errorDetails)
          errorDetails.innerHTML = `<p class="font-bold mb-2">Erreur Réseau:</p><p class="text-sm">Impossible de communiquer avec le serveur.</p>`;
        if (loginErrorPopup) loginErrorPopup.classList.add(CLASS_SHOW);
      }
    });
  }

  // -------------------------------------------------------------
  // --- 14. GESTION DES POPUPS DE SESSION (Login/Logout) ---
  // -------------------------------------------------------------
  const handleSessionMessages = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const successMessageElement = document.getElementById("success-message");
    const welcomePopup = document.getElementById("welcome-popup");

    if (urlParams.has("login") && urlParams.get("login") === "success") {
      // Le header PHP a géré l'affichage du popup de bienvenue si le flag de session était là.
      // Si la redirection a eu lieu, on nettoie l'URL.
      history.replaceState(null, "", window.location.pathname);
    } else if (
      urlParams.has("logout") &&
      urlParams.get("logout") === "success"
    ) {
      // Déclencher le popup de succès de déconnexion
      const successPopup = document.getElementById("popup-success");
      if (successPopup) {
        successPopup.querySelector("h3").textContent = "DÉCONNEXION RÉUSSIE";
        successPopup.querySelector("#success-message").textContent =
          "👋 Déconnexion réussie. À bientôt !";
        showGlobalPopup(successPopup, 3000);
      }
      history.replaceState(null, "", window.location.pathname);
    }

    // Fermeture manuelle du popup de bienvenue (si le PHP l'a affiché)
    const welcomeCloseBtn = document.getElementById("welcome-close-btn");
    if (welcomePopup && welcomeCloseBtn) {
      // Le popup est affiché par défaut si la session est nouvelle (voir header.inc.php)
      welcomeCloseBtn.addEventListener("click", (e) => {
        e.preventDefault();
        welcomePopup.classList.remove(CLASS_SHOW);
        setTimeout(() => {
          welcomePopup.style.display = "none";
        }, 300);
      });
    }
  };

  handleSessionMessages();
}); // Fin de DOMContentLoaded

// ----------------------------------------------------
// --- Fonction de recherche (Globale pour Section 3) (MODIFIÉ) ---
// ----------------------------------------------------
function performProductSearch() {
  const searchInput = document.getElementById("search-input");
  const resultCountElement = document.getElementById("search-result-count");
  const query = searchInput.value.toLowerCase();
  const productCards = document.querySelectorAll(".product-card");

  if (productCards.length === 0) {
    if (resultCountElement) {
      resultCountElement.textContent = "Aucun produit trouvé.";
    }
    return;
  }

  let visibleCount = 0;

  productCards.forEach((card) => {
    const name =
      card.querySelector(".product-title")?.textContent.toLowerCase() || "";
    const description =
      card.querySelector(".product-description")?.textContent.toLowerCase() ||
      "";

    if (name.includes(query) || description.includes(query)) {
      card.classList.remove(CLASS_HIDDEN);
      visibleCount++;
    } else {
      card.classList.add(CLASS_HIDDEN);
    }
  });

  // MISE À JOUR: Affichage du compte de résultats (Problème 6)
  if (resultCountElement) {
    if (visibleCount > 0) {
      resultCountElement.textContent = `${visibleCount} produit(s) affiché(s) pour "${query}"`;
    } else {
      resultCountElement.textContent = `Aucun produit trouvé pour "${query}".`;
    }
  }
}

// -------------------------------------------------------------
// --- Logique Social Media (MAINTENUE HORS DOMContentLoaded) ---
// -------------------------------------------------------------
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
