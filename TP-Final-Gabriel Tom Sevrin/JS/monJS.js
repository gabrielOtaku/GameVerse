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
 * @param {HTMLElement} popupElement
 * @param {number} duration
 */
const showGlobalPopup = (popupElement, duration = 4000) => {
  if (popupElement) {
    popupElement.style.display = "flex";
    popupElement.classList.add(CLASS_SHOW);

    setTimeout(() => {
      popupElement.classList.remove(CLASS_SHOW);
      setTimeout(() => {
        popupElement.style.display = "none";
      }, 300);
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
  window.open(mailtoLink, "_blank");
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
  // 2. MENU DEROULANT AUTHENTIFICATION (FIX 1)
  // ----------------------------------------------------
  const authBtn = document.getElementById("auth-btn");
  const authMenu = document.getElementById("auth-menu");
  const authContainer = document.querySelector(".auth-container");

  if (authBtn && authMenu && authContainer) {
    // Logique d'affichage du menu
    authBtn.addEventListener("click", (event) => {
      event.stopPropagation();
      authBtn.classList.toggle(CLASS_ACTIVE);
      authMenu.classList.toggle(CLASS_SHOW);
    });

    // Fermeture du menu si on clique sur un lien interne
    authMenu.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        authBtn.classList.remove(CLASS_ACTIVE);
        authMenu.classList.remove(CLASS_SHOW);
      });
    });

    // Fermeture si on clique ailleurs
    document.addEventListener("click", (event) => {
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
  // 3. BARRE DE RECHERCHE + AUTOCOMPLÉTION + OVERLAY PREVIEW
  // ----------------------------------------------------
  const searchBtn = document.getElementById("search-btn");
  const searchContainer = document.querySelector(".search-container");
  const searchInput = document.getElementById("search-input");
  const autocompleteResults = document.getElementById("autocomplete-results");

  // Overlay de preview pour l'autocomplétion de recherche
  let previewOverlay = document.getElementById("game-preview-overlay");
  if (!previewOverlay) {
    previewOverlay = document.createElement("div");
    previewOverlay.id = "game-preview-overlay";
    // Styles pour le preview de recherche
    previewOverlay.style.cssText = `display:none;position:fixed;z-index:9999;background:#191a21;border:2px solid #00ffcc;border-radius:12px;box-shadow:0 4px 24px #00ffcc99;padding:18px;min-width:240px;max-width:340px;max-height:220px;color:#b0f6e5;pointer-events:none;opacity:0;transition:opacity .2s;`;
    document.body.appendChild(previewOverlay);
  }

  const performSearchAndRedirect = (query) => {
    const path = window.location.pathname.includes("/PHP/")
      ? "produit.php"
      : "PHP/produit.php";
    window.location.href = `${path}?q=${encodeURIComponent(query.trim())}`;
  };

  if (searchBtn && searchContainer && searchInput) {
    searchBtn.addEventListener("click", (event) => {
      event.stopPropagation();

      if (searchContainer.classList.contains(CLASS_EXPANDED)) {
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
          searchContainer.classList.remove(CLASS_EXPANDED);
        }
      } else {
        searchContainer.classList.add(CLASS_EXPANDED);
        searchInput.focus();
      }
    });

    document.addEventListener("click", (event) => {
      if (
        !searchContainer.contains(event.target) &&
        searchContainer.classList.contains(CLASS_EXPANDED) &&
        searchInput.value === ""
      ) {
        searchContainer.classList.remove(CLASS_EXPANDED);
        if (autocompleteResults)
          autocompleteResults.classList.remove(CLASS_SHOW);
      }
    });

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
        if (autocompleteResults)
          autocompleteResults.classList.remove(CLASS_SHOW);
      }
    });

    // Logique d'autocomplétion
    searchInput.addEventListener("input", (event) => {
      if (!autocompleteResults) return;

      const query = searchInput.value.trim().toLowerCase();
      autocompleteResults.innerHTML = "";

      // Si le champ est vide, on efface les résultats
      if (query.length === 0) {
        autocompleteResults.classList.remove(CLASS_SHOW);
        previewOverlay.style.opacity = "0";
        setTimeout(() => (previewOverlay.style.display = "none"), 150);
        if (
          window.location.pathname.includes("produit.php") ||
          window.location.pathname.includes("produit")
        ) {
          if (typeof performProductSearch === "function") {
            performProductSearch();
          }
        }
        return;
      }

      const productsToSearch =
        typeof ALL_PRODUCTS_DATA !== "undefined" ? ALL_PRODUCTS_DATA : [];

      if (query.length < 2 || productsToSearch.length === 0) {
        autocompleteResults.classList.remove(CLASS_SHOW);
        return;
      }

      const filteredProducts = productsToSearch.filter((product) =>
        product.name.toLowerCase().includes(query)
      );

      if (filteredProducts.length > 0) {
        filteredProducts.slice(0, 5).forEach((product) => {
          const resultItem = document.createElement("div");
          resultItem.className = "autocomplete-item";

          let imagePath = window.location.pathname.includes("/PHP/")
            ? `../IMG/${product.image}`
            : `IMG/${product.image}`;

          resultItem.innerHTML = `
              <img src="${imagePath}" alt="${
            product.name
          }" class="autocomplete-img">
              <div class="autocomplete-text">
                  <h4 class="font-bold text-neon-cyan">${product.name}</h4>
                  <p class="text-xs text-gray-400 line-clamp-1">${product.description.substring(
                    0,
                    70
                  )}...</p>
              </div>
          `;
          resultItem.addEventListener("click", () => {
            searchInput.value = product.name;
            if (
              window.location.pathname.includes("produit.php") ||
              window.location.pathname.includes("produit")
            ) {
              if (typeof performProductSearch === "function") {
                performProductSearch();
              }
            } else {
              performSearchAndRedirect(product.name);
            }
            autocompleteResults.classList.remove(CLASS_SHOW);
            previewOverlay.style.opacity = "0";
            setTimeout(() => (previewOverlay.style.display = "none"), 150);
          });

          // === OVERLAY PREVIEW LOGIC ===
          resultItem.addEventListener("mouseenter", (e) => {
            const searchRect = searchContainer.getBoundingClientRect();

            previewOverlay.innerHTML = `
                <div style="display:flex;align-items:start;">
                  <img src="${imagePath}" alt="${
              product.name
            }" style="width:74px;height:74px;border-radius:8px;object-fit:cover;margin-right:12px;border:1.5px solid #00ffcc;">
                  <div>
                    <h4 style="color:#00ffcc;" class="font-bold mb-1">${
                      product.name
                    }</h4>
                    <div style="font-size:.93em;color:#66fff6;">${product.description.substring(
                      0,
                      100
                    )}...</div>
                  </div>
                </div>`;

            // Positionnement: à droite du conteneur de recherche
            previewOverlay.style.top = `${searchRect.top + window.scrollY}px`;
            previewOverlay.style.left = `${searchRect.right + 10}px`;

            previewOverlay.style.display = "block";
            previewOverlay.style.opacity = "1";
          });
          resultItem.addEventListener("mouseleave", () => {
            previewOverlay.style.opacity = "0";
            setTimeout(() => {
              previewOverlay.style.display = "none";
            }, 150);
          });

          autocompleteResults.appendChild(resultItem);
        });
        autocompleteResults.classList.add(CLASS_SHOW);
      } else {
        autocompleteResults.classList.remove(CLASS_SHOW);
        previewOverlay.style.opacity = "0";
        setTimeout(() => (previewOverlay.style.display = "none"), 150);
      }
    });

    // Recherche via param 'q' dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const urlSearchQuery = urlParams.get("q");
    if (
      urlSearchQuery &&
      (window.location.pathname.includes("produit.php") ||
        window.location.pathname.includes("produit"))
    ) {
      searchInput.value = urlSearchQuery;
      searchContainer.classList.add(CLASS_EXPANDED);
      if (typeof performProductSearch === "function") {
        setTimeout(performProductSearch, 100);
      }
    }
  }

  // -----------------------------------------------------
  // 4. POP-UP DE PARAMETRES (Fermeture/Ouverture)
  // -----------------------------------------------------
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
  // 5. Logique des Switches (Langue/Thème)
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
  // 6. Carrousel d'Accueil + Miniatures + Overlay Preview
  // -------------------------------------------------------------
  const mainCarousel = document.getElementById("carouselExampleIndicators");
  const thumbnails = document.querySelectorAll(".thumbnail-item");

  // --- NEON COLOR LOGIQUE ---
  const updateNeonColor = (activeIndex) => {
    const activeSlide = mainCarousel?.querySelector(
      `.carousel-item:nth-child(${activeIndex + 1})`
    );
    if (!activeSlide) return;

    const neonColor = activeSlide.getAttribute("data-neon-color") || "#00ffcc";
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
    //   Création de l'Overlay pour le carrousel (Miniature Preview)
    let carouselPreview = document.getElementById("carousel-preview-overlay");
    if (!carouselPreview) {
      carouselPreview = document.createElement("div");
      carouselPreview.id = "carousel-preview-overlay";
      carouselPreview.style.cssText = `display:none;position:fixed;z-index:9999;background:#111a;border:2px solid #00ffcc;border-radius:9px;box-shadow:0 2px 18px #00ffcc80;padding:8px;max-width:200px; max-height:100px; pointer-events:none;opacity:0;transition:opacity .15s;`;
      document.body.appendChild(carouselPreview);
    }

    const carouselInstance =
      typeof bootstrap !== "undefined" && bootstrap.Carousel.getInstance
        ? bootstrap.Carousel.getInstance(mainCarousel)
        : null;

    if (carouselInstance) {
      mainCarousel.addEventListener("slide.bs.carousel", function (event) {
        const nextSlideIndex = event.to;
        updateNeonColor(nextSlideIndex);
      });
    }

    thumbnails.forEach((thumb, index) => {
      thumb.addEventListener("click", () => {
        if (typeof bootstrap !== "undefined" && bootstrap.Carousel) {
          const carousel = bootstrap.Carousel.getInstance(mainCarousel);
          if (carousel) {
            carousel.to(index);
          }
        }
      });

      //  LOGIQUE DU HOVER (OVERLAY PREVIEW)
      thumb.addEventListener("mouseenter", (e) => {
        const thumbRect = thumb.getBoundingClientRect();
        carouselPreview.innerHTML = `<img src="${thumb.src}" alt="Prévisualisation" style="width:180px;max-height:80px;object-fit:cover;border-radius:6px;">`;

        const previewWidth = 196;
        const previewHeight = 96;

        // Positionner l'overlay au-dessus de la miniature
        carouselPreview.style.top = `${
          thumbRect.top + window.scrollY - previewHeight - 10
        }px`;
        carouselPreview.style.left = `${
          thumbRect.left + thumbRect.width / 2 - previewWidth / 2
        }px`;

        carouselPreview.style.display = "block";
        carouselPreview.style.opacity = "1";
      });

      thumb.addEventListener("mouseleave", () => {
        carouselPreview.style.opacity = "0";
        setTimeout(() => {
          carouselPreview.style.display = "none";
        }, 120);
      });
    });
    // Initial mini
    const initialActiveIndex = 0;
    if (thumbnails[initialActiveIndex]) updateNeonColor(initialActiveIndex);
  }

  // -------------------------------------------------------------
  // 7. Logique du Catalogue de Produits & MessageBox d'erreur
  // -------------------------------------------------------------
  if (typeof VanillaTilt !== "undefined") {
    VanillaTilt.init(document.querySelectorAll(".product-card"));
  }

  //  gérer les messages d'erreur
  function showCustomMessage(
    productName,
    productImage,
    quantity,
    isError = false
  ) {
    const messageBox = document.getElementById("custom-message-box");
    const messageText = document.getElementById("message-text");
    const messageImage = document.getElementById("message-image");
    const confirmationMessage = messageBox?.querySelector(
      ".confirmation-message"
    );
    const messageIcon = messageBox?.querySelector(".message-icon");

    if (
      messageBox &&
      messageText &&
      messageImage &&
      confirmationMessage &&
      messageIcon
    ) {
      if (isError) {
        messageBox.classList.add("error-box");
        confirmationMessage.textContent = "Erreur de Stock !";
        messageIcon.className = "fas fa-exclamation-triangle message-icon";
        messageText.textContent = productName; // Le message d'erreur complet
      } else {
        messageBox.classList.remove("error-box");
        confirmationMessage.textContent = "Ajouté au panier !";
        messageIcon.className = "fas fa-check-circle message-icon";
        messageText.textContent = `${quantity}x ${productName}`;
      }

      messageImage.src = productImage;
      messageImage.alt = productName;

      messageBox.classList.add(CLASS_SHOW);

      setTimeout(() => {
        messageBox.classList.remove(CLASS_SHOW);
        messageBox.classList.remove("error-box"); // Nettoyage
      }, 3000);
    }
  }

  // ajouter la validation du stock lors de l'ajout au panier
  window.addToCart = function (buttonElement) {
    const productId = buttonElement.getAttribute("data-product-id");
    const productName = buttonElement.getAttribute("data-product-name");
    const productImage = buttonElement.getAttribute("data-product-image");
    const productStock = parseInt(
      buttonElement.getAttribute("data-product-stock")
    );

    const quantityInput = document.getElementById(`qty-${productId}`);
    const quantity = parseInt(quantityInput?.value) || 1;

    // VÉRIFICATION DU STOCK
    if (productStock <= 0) {
      showCustomMessage(
        `Rupture de stock pour ${productName}.`,
        productImage,
        0,
        true
      );
      return;
    }

    if (quantity > productStock) {
      showCustomMessage(
        `Seulement ${productStock} unité(s) disponible(s) pour ${productName}.`,
        productImage,
        0,
        true
      );
      //  quantité maximale disponible
      if (quantityInput) quantityInput.value = productStock;
      return;
    }

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
  // --- 9. NEXUS AI CHATBOT (Logique Bulle Flottante) ---
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

  // --- Toggle de la Bulle ---
  if (nexusToggleBtn && nexusChatbox && nexusCloseBtn) {
    // S'assure que le bouton est visible au chargement
    nexusToggleBtn.classList.remove(CLASS_HIDDEN);
    nexusChatbox.classList.remove(CLASS_SHOW);

    nexusToggleBtn.addEventListener("click", () => {
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

  // --- Fonction d'affichage des messages ---
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

    // Ajout des sources
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

  // --- Gestion de l'état d'envoi ---
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
  // 11. Logique du Formulaire de Contact & Popups
  // -------------------------------------------------------------
  const contactForm = document.getElementById("contact-form");
  const contactSuccessPopup = document.getElementById("contact-success-popup");
  const contactErrorPopup = document.getElementById("contact-error-popup");

  if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault();

      // Simple validation client (vérifier si les champs requis sont remplis)
      const nom = document.getElementById("nom")?.value.trim();
      const sujet = document.getElementById("sujet")?.value.trim();
      const courriel = document.getElementById("courriel")?.value.trim();
      const cellulaire = document.getElementById("cellulaire")?.value.trim();
      const commentaire = document.getElementById("commentaire")?.value.trim();

      // Si la validation de base est manquante ou si l'un des champs requis est vide
      if (!nom || !sujet || !courriel || !cellulaire || !commentaire) {
        // ce bloc sert de fallback.
        showGlobalPopup(contactErrorPopup);
        return;
      }

      const formData = new FormData(contactForm);
      const name = formData.get("nom") || "Client GameVerse";
      const subject = formData.get("sujet") || "Requête sans sujet";
      const email = formData.get("courriel");
      const phone = formData.get("cellulaire") || "";
      const message = formData.get("commentaire");

      const destinationEmail = "gabrielherve94250@gmail.com";
      const mailBody = `Nom et Prénom: ${name}\nCourriel: ${email}\nTéléphone: ${phone}\n\nMessage:\n${message}`;

      // 1. Afficher le popup de succès immédiatement
      const delayBeforeMail = 3000;
      showGlobalPopup(contactSuccessPopup, delayBeforeMail);

      // 2. Ouvrir le client de messagerie après un délai
      setTimeout(() => {
        openMailClient(destinationEmail, subject, mailBody);
      }, delayBeforeMail);

      // 3. Réinitialiser le formulaire
      contactForm.reset();
    });
  }

  // -------------------------------------------------------------
  // 12. Logique du Formulaire d'inscription
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
  // Element d'affichage du mot de passe
  const passwordSuggestionDisplay = document.getElementById(
    "password-suggestion-display"
  );

  // --- Noms d'animations ---
  const ANIM_GREET = "Salutation";
  const ANIM_HAPPY = "Content";
  const ANIM_ERROR = "CourtCircuit";
  const ANIM_RECOVER = "Réveil";

  // --- Fonction pour jouer une animation  ---
  const playBotAnimation = (name, loop = false) => {
    if (botModel) {
      // S'assure que l'animation est appliquée même si ce n'est pas un loop
      botModel.animationName = name;
      botModel.setAttribute("autoplay", true);
      botModel.setAttribute("loop", loop);

      // Si ce n'est pas un loop, ajoute un écouteur pour revenir à l'état d'inactivité
      if (!loop) {
        botModel.addEventListener(
          "finished",
          () => {
            setTimeout(() => {
              playBotAnimation(ANIM_GREET, true);
            }, 500);
          },
          { once: true }
        );
      }
    }
  };

  if (registrationForm) {
    // Animation robot inscrit vivant
    if (botModel) {
      let idleAnims = [ANIM_GREET, ANIM_HAPPY];

      function playIdleRandomAnim() {
        // S'assure de ne pas interrompre les popups
        if (
          document
            .getElementById("popup-success")
            ?.classList.contains("show") ||
          document.getElementById("popup-error")?.classList.contains("show")
        ) {
          return;
        }
        let anim = idleAnims[Math.floor(Math.random() * idleAnims.length)];
        playBotAnimation(anim, true); // loop=true pour l'inactivité
      }

      // Démarrer l'animation idle après le chargement du modèle
      botModel.addEventListener("load", () => {
        playIdleRandomAnim();
      });

      // Répéter aléatoirement une animation idle (pour le mouvement de fond)
      setInterval(() => {
        playIdleRandomAnim();
      }, 25000);
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

          // Logique de copie au clic
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

        // Jouer l'animation de succès du robot
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
      playBotAnimation(anim); // Joue l'animation d'erreur ou de succès (non-loop)
    };

    const hideRegPopup = (popup, redirect = false) => {
      if (popup) popup.classList.remove("show");
      // Si on ferme un popup sans redirection immédiate
      if (popup === regErrorPopup) {
        playBotAnimation(ANIM_RECOVER); // Le robot se relève, puis passe à l'idle
      }

      if (redirect) {
        const redirectPath = window.location.pathname.includes("/PHP/")
          ? "seConnecter.php"
          : "PHP/seConnecter.php";
        window.location.href = redirectPath;
      }
    };

    if (regErrorCloseBtn) {
      regErrorCloseBtn.addEventListener("click", () => {
        hideRegPopup(regErrorPopup);
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

      // Validation côté client
      const email = formData.get("courriel");
      const password = formData.get("mot_de_passe");
      const dob = formData.get("date_naissance");

      if (!email || !email.includes("@"))
        clientErrors["courriel"] = "Format de courriel invalide.";
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
          regErrorDetails.innerHTML = `<p class="font-bold mb-2">Erreur Réseau:</p><p class="text-sm">Impossible de communiquer avec le serveur.</p>`;
        showRegPopup(regErrorPopup, ANIM_ERROR);
      }
    });
  }

  // -------------------------------------------------------------
  // 13. Logique du Formulaire de Connexion
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
          // Connexion réussie, affiche le popup de succès
          if (successMessageElement)
            successMessageElement.textContent = result.message;
          if (loginSuccessPopup) showGlobalPopup(loginSuccessPopup, 2000);

          setTimeout(() => {
            window.location.href = result.redirect;
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
  // 14. GESTION DES POPUPS DE SESSION (Login/Logout)
  // -------------------------------------------------------------
  const handleSessionMessages = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const welcomePopup = document.getElementById("welcome-popup");

    if (urlParams.has("login") && urlParams.get("login") === "success") {
      history.replaceState(null, "", window.location.pathname);
    } else if (
      urlParams.has("logout") &&
      urlParams.get("logout") === "success"
    ) {
      const successPopup = document.getElementById("popup-success");
      if (successPopup) {
        successPopup.querySelector("h3").textContent = "DÉCONNEXION RÉUSSIE";
        successPopup.querySelector("#success-message").textContent =
          "👋 Déconnexion réussie. À bientôt !";
        showGlobalPopup(successPopup, 3000);
      }
      history.replaceState(null, "", window.location.pathname);
    }
    const welcomeCloseBtn = document.getElementById("welcome-close-btn");
    if (welcomePopup && welcomeCloseBtn) {
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
});

// ----------------------------------------------------
// --- Fonction de recherche catalogue de jeux ---
// ----------------------------------------------------
function performProductSearch() {
  const searchInput = document.getElementById("search-input");
  const resultCountElement = document.getElementById("search-result-count");
  const query = searchInput.value.toLowerCase();
  const productCards = document.querySelectorAll(".product-card");
  const autocompleteResults = document.getElementById("autocomplete-results");

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
  if (resultCountElement) {
    if (visibleCount > 0) {
      resultCountElement.textContent = `${visibleCount} produit(s) affiché(s) pour "${query}"`;
    } else {
      resultCountElement.textContent = `Aucun produit trouvé pour "${query}".`;
    }
  }

  if (autocompleteResults) autocompleteResults.classList.remove(CLASS_SHOW);
}

// --- Logique Social Media ---
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
    // On suppose que VanillaTilt est chargé par un script externe
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
// ----------------------------------------------------
// --- Gestion du Profil ---
// ----------------------------------------------------

// Logique d'édition du formulaire de profil
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("profile-form");
  const editBtn = document.getElementById("edit-profile-btn");
  const saveBtn = document.getElementById("save-profile-btn");
  const cancelBtn = document.getElementById("cancel-edit-btn");
  const fields = form.querySelectorAll("input:not([readonly])");

  let isEditing = false;

  const toggleEditMode = (enable) => {
    isEditing = enable;
    fields.forEach((field) => {
      field.disabled = !enable;
      field.classList.toggle("item-editable", enable);
    });

    editBtn.classList.toggle("hidden", enable);
    saveBtn.classList.toggle("hidden", !enable);
    cancelBtn.classList.toggle("hidden", !enable);

    // Activer/Désactiver le bouton Enregistrer (initialement désactivé)
    saveBtn.disabled = !enable;
    if (enable) {
      saveBtn.disabled = true; // le met à jour seulement quand un champ change
    }
  };

  // Événement pour activer le mode édition
  editBtn.addEventListener("click", () => toggleEditMode(true));

  // Événement pour annuler l'édition
  cancelBtn.addEventListener("click", () => {
    form.reset();
    toggleEditMode(false);
  });

  // Événement pour détecter les changements et activer le bouton Save
  fields.forEach((field) => {
    field.addEventListener("input", () => {
      if (isEditing) {
        saveBtn.disabled = false;
      }
    });
  });

  // Événement pour la soumission du formulaire
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    alert("Données de profil enregistrées avec succès (simulation) !");
    toggleEditMode(false);
  });

  // Désactiver le mode édition au chargement de la page et désactiver les champs au départ
  toggleEditMode(false);
});
