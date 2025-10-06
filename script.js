// script.js - JavaScript untuk PeRaSa Website

// ... (kode yang sudah ada tetap sama)

// Update bagian dark mode untuk konsistensi dengan PHP
function addThemeToggle() {
    const themeToggle = document.createElement('button');
    themeToggle.id = 'theme-toggle';
    themeToggle.innerHTML = '🌙 Dark Mode';
    themeToggle.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        background: var(--secondary-color);
        color: var(--text-color);
        border: none;
        padding: 12px 20px;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        transition: all var(--transition);
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    document.body.appendChild(themeToggle);
    
    elements.themeToggle = themeToggle;
    themeToggle.addEventListener('click', toggleDarkMode);
}

// ... (sisa kode tetap sama)