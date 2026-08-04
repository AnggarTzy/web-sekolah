/**
 * =====================================================
 * FILE: frontend/js/script.js
 * SMP Muhammadiyah 6 Krian
 * JavaScript Lengkap
 * =====================================================
 */

// ==========================================
// 1. STRICT MODE & READY
// ==========================================
'use strict';

document.addEventListener('DOMContentLoaded', function() {
    console.log('SMP Muhammadiyah 6 Krian - Website loaded');
    
    // Inisialisasi semua fungsi
    initNavbar();
    initScrollAnimation();
    initCounter();
    initBackToTop();
    initSmoothScroll();
    initFormValidation();
    initMobileMenu();
    initHeroSlider();
    initStatsCounter();
    initNewsletter();
    initGalleryLightbox();
});

// ==========================================
// 2. NAVBAR FUNCTIONS
// ==========================================
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Close mobile menu on link click
    const navLinks = document.querySelectorAll('.navbar .nav-link');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    });
    
    // Auto close on outside click (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
            const isNavbar = navbar.contains(e.target);
            const isToggler = navbarToggler.contains(e.target);
            if (!isNavbar && !isToggler) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse && navbarCollapse.classList.contains('show')) {
                    bsCollapse.hide();
                }
            }
        }
    });
}

// ==========================================
// 3. SCROLL ANIMATION (Intersection Observer)
// ==========================================
function initScrollAnimation() {
    const animateElements = document.querySelectorAll('.animate-on-scroll, .quick-item, .berita-card, .prestasi-card, .galeri-item');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animateElements.forEach(function(el) {
            observer.observe(el);
        });
    } else {
        // Fallback for older browsers
        animateElements.forEach(function(el) {
            el.classList.add('animated');
        });
    }
}

// ==========================================
// 4. COUNTER ANIMATION
// ==========================================
function initCounter() {
    const counters = document.querySelectorAll('.counter-number');
    
    if (counters.length === 0) return;
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const target = entry.target;
                const targetValue = parseInt(target.getAttribute('data-target'));
                const duration = 2000;
                const stepTime = 16;
                const totalSteps = duration / stepTime;
                const increment = targetValue / totalSteps;
                let currentValue = 0;
                
                const updateCounter = function() {
                    currentValue += increment;
                    if (currentValue >= targetValue) {
                        target.textContent = targetValue + '+';
                        return;
                    }
                    target.textContent = Math.floor(currentValue) + '+';
                    requestAnimationFrame(updateCounter);
                };
                
                updateCounter();
                observer.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(function(counter) {
        observer.observe(counter);
    });
}

// ==========================================
// 5. BACK TO TOP BUTTON
// ==========================================
function initBackToTop() {
    // Create button if not exists
    let backToTop = document.querySelector('.back-to-top');
    if (!backToTop) {
        backToTop = document.createElement('button');
        backToTop.className = 'back-to-top';
        backToTop.innerHTML = '<i class="bi bi-chevron-up"></i>';
        backToTop.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary, #1A3C6E);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(26, 60, 110, 0.3);
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            z-index: 999;
        `;
        document.body.appendChild(backToTop);
        
        // Hover effect
        backToTop.addEventListener('mouseenter', function() {
            this.style.background = '#F5C518';
            this.style.color = '#1A3C6E';
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });
        
        backToTop.addEventListener('mouseleave', function() {
            this.style.background = 'var(--primary, #1A3C6E)';
            this.style.color = 'white';
            this.style.transform = 'translateY(0) scale(1)';
        });
    }
    
    // Show/hide on scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.style.opacity = '1';
            backToTop.style.visibility = 'visible';
            backToTop.style.transform = 'translateY(0)';
        } else {
            backToTop.style.opacity = '0';
            backToTop.style.visibility = 'hidden';
            backToTop.style.transform = 'translateY(20px)';
        }
    });
    
    // Click to scroll top
    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ==========================================
// 6. SMOOTH SCROLL FOR ANCHOR LINKS
// ==========================================
function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 0;
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ==========================================
// 7. FORM VALIDATION
// ==========================================
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('[required]');
            let isValid = true;
            
            inputs.forEach(function(input) {
                const errorElement = input.parentElement.querySelector('.error-message');
                const value = input.value.trim();
                
                // Remove existing error
                input.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.remove();
                }
                
                // Validation
                if (!value) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    showError(input, 'Field ini wajib diisi');
                    return;
                }
                
                // Email validation
                if (input.type === 'email' && value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        input.classList.add('is-invalid');
                        showError(input, 'Format email tidak valid');
                        return;
                    }
                }
                
                // Phone validation
                if (input.type === 'tel' && value) {
                    const phoneRegex = /^[0-9+\-\s()]{10,15}$/;
                    if (!phoneRegex.test(value)) {
                        isValid = false;
                        input.classList.add('is-invalid');
                        showError(input, 'Format nomor telepon tidak valid');
                        return;
                    }
                }
                
                // Password confirmation
                const confirmField = input.getAttribute('data-confirm');
                if (confirmField) {
                    const confirmInput = form.querySelector(`[name="${confirmField}"]`);
                    if (confirmInput && value !== confirmInput.value) {
                        isValid = false;
                        input.classList.add('is-invalid');
                        showError(input, 'Password tidak cocok');
                        return;
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Real-time validation
        form.querySelectorAll('[required]').forEach(function(input) {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
    });
}

function validateField(input) {
    const errorElement = input.parentElement.querySelector('.error-message');
    const value = input.value.trim();
    
    if (errorElement) {
        errorElement.remove();
    }
    
    if (input.hasAttribute('required') && !value) {
        input.classList.add('is-invalid');
        showError(input, 'Field ini wajib diisi');
        return false;
    }
    
    if (input.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            input.classList.add('is-invalid');
            showError(input, 'Format email tidak valid');
            return false;
        }
    }
    
    input.classList.remove('is-invalid');
    return true;
}

function showError(input, message) {
    const error = document.createElement('div');
    error.className = 'error-message';
    error.style.cssText = `
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    `;
    error.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
    input.parentElement.appendChild(error);
}

// ==========================================
// 8. MOBILE MENU ENHANCEMENT
// ==========================================
function initMobileMenu() {
    const menuToggle = document.querySelector('.navbar-toggler');
    const menuIcon = menuToggle?.querySelector('.navbar-toggler-icon');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const isOpen = this.classList.contains('collapsed');
            if (isOpen) {
                // Menu opened
                document.body.style.overflow = 'hidden';
            } else {
                // Menu closed
                document.body.style.overflow = '';
            }
        });
    }
}

// ==========================================
// 9. HERO SLIDER (if multiple slides)
// ==========================================
function initHeroSlider() {
    const heroSlides = document.querySelectorAll('.hero-slide');
    if (heroSlides.length > 1) {
        let currentSlide = 0;
        const totalSlides = heroSlides.length;
        
        // Hide all slides except first
        heroSlides.forEach(function(slide, index) {
            if (index !== 0) {
                slide.style.display = 'none';
            }
        });
        
        // Auto rotate
        setInterval(function() {
            heroSlides.forEach(function(slide) {
                slide.style.display = 'none';
            });
            
            currentSlide = (currentSlide + 1) % totalSlides;
            heroSlides[currentSlide].style.display = 'block';
            
            // Add fade animation
            heroSlides[currentSlide].classList.add('fade-in');
            setTimeout(function() {
                heroSlides[currentSlide].classList.remove('fade-in');
            }, 1000);
            
        }, 5000);
    }
}

// ==========================================
// 10. STATS COUNTER (Hero stats)
// ==========================================
function initStatsCounter() {
    const stats = document.querySelectorAll('.hero-badge .stat span');
    
    stats.forEach(function(stat) {
        const text = stat.textContent;
        const number = parseInt(text.replace(/[^0-9]/g, ''));
        
        if (number && !isNaN(number)) {
            const originalText = text;
            stat.setAttribute('data-target', number);
            stat.textContent = '0';
        }
    });
}

// ==========================================
// 11. NEWSLETTER FORM
// ==========================================
function initNewsletter() {
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput?.value.trim();
            
            if (email) {
                // Simulate API call
                const button = this.querySelector('button');
                const originalText = button.textContent;
                
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
                
                setTimeout(function() {
                    button.disabled = false;
                    button.innerHTML = '<i class="bi bi-check-circle"></i> Berhasil!';
                    emailInput.value = '';
                    
                    setTimeout(function() {
                        button.innerHTML = originalText;
                    }, 3000);
                }, 1500);
            }
        });
    }
}

// ==========================================
// 12. GALLERY LIGHTBOX
// ==========================================
function initGalleryLightbox() {
    const galleryItems = document.querySelectorAll('.galeri-item');
    
    galleryItems.forEach(function(item) {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            if (img) {
                const imgSrc = img.getAttribute('src');
                const imgAlt = img.getAttribute('alt') || 'Galeri';
                showLightbox(imgSrc, imgAlt);
            }
        });
    });
}

function showLightbox(src, alt) {
    // Create lightbox container
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox-overlay';
    lightbox.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    const img = document.createElement('img');
    img.src = src;
    img.alt = alt;
    img.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    `;
    
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '✕';
    closeBtn.style.cssText = `
        position: absolute;
        top: 20px;
        right: 30px;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: transform 0.3s ease;
    `;
    closeBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'rotate(90deg)';
    });
    closeBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'rotate(0)';
    });
    
    lightbox.appendChild(img);
    lightbox.appendChild(closeBtn);
    document.body.appendChild(lightbox);
    
    // Animate in
    requestAnimationFrame(function() {
        lightbox.style.opacity = '1';
        img.style.transform = 'scale(1)';
    });
    
    // Close on click
    lightbox.addEventListener('click', function(e) {
        if (e.target === this || e.target === closeBtn) {
            closeLightbox(lightbox);
        }
    });
    
    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox(lightbox);
        }
    });
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeLightbox(lightbox) {
    const img = lightbox.querySelector('img');
    img.style.transform = 'scale(0.9)';
    lightbox.style.opacity = '0';
    
    setTimeout(function() {
        lightbox.remove();
        document.body.style.overflow = '';
    }, 300);
}

// ==========================================
// 13. LOADING SCREEN
// ==========================================
(function showLoadingScreen() {
    const loadingScreen = document.querySelector('.loading-screen');
    if (loadingScreen) {
        window.addEventListener('load', function() {
            loadingScreen.classList.add('fade-out');
            setTimeout(function() {
                loadingScreen.style.display = 'none';
            }, 500);
        });
    }
})();

// ==========================================
// 14. RESPONSIVE TABLE HANDLER
// ==========================================
function initResponsiveTable() {
    const tables = document.querySelectorAll('.table-responsive');
    tables.forEach(function(table) {
        const wrapper = document.createElement('div');
        wrapper.className = 'table-scroll-wrapper';
        wrapper.style.cssText = `
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 1rem 0;
        `;
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });
}

// ==========================================
// 15. SEARCH FUNCTIONALITY
// ==========================================
function initSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchResults = document.querySelector('.search-results');
    
    if (searchInput && searchResults) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.classList.remove('show');
                return;
            }
            
            searchTimeout = setTimeout(function() {
                performSearch(query);
            }, 300);
        });
    }
}

function performSearch(query) {
    // This would connect to API in production
    console.log('Searching for:', query);
    // Simulate search
    const results = document.querySelector('.search-results');
    if (results) {
        results.innerHTML = `
            <div class="search-result-item">
                <i class="bi bi-search"></i>
                <span>Hasil pencarian untuk "${query}"</span>
            </div>
            <div class="search-result-item">
                <i class="bi bi-file-text"></i>
                <span>Berita: Menemukan ${Math.floor(Math.random() * 5) + 1} hasil</span>
            </div>
        `;
        results.classList.add('show');
    }
}

// ==========================================
// 16. CONSOLE WELCOME
// ==========================================
console.log(`%c
╔═══════════════════════════════════════════╗
║                                           ║
║   SMP MUHAMMADIYAH 6 KRIAN               ║
║   Website Resmi Sekolah                  ║
║                                           ║
║   "Maju, Berkemajuan"                    ║
║                                           ║
╚═══════════════════════════════════════════╝
`, 'color: #F5C518; background: #1A3C6E; font-size: 14px; font-weight: bold; padding: 10px;');

// ==========================================
// END OF FILE
// ==========================================