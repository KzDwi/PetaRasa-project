// script.js - JavaScript untuk PeRaSa Website

// Data restoran lokal Samarinda (fallback data)
const localRestaurants = [
    {
        id: "kikitaru",
        name: "Kikitaru (Ex. Taberu)",
        description: "Restoran Jepang dengan suasana autentik",
        pictureId: "",
        city: "Samarinda",
        rating: 4.5,
        address: "SCP Mall Lt. 2, Samarinda",
        price: "$$$",
        category: "Jepang"
    },
    {
        id: "nasi-kuning-mbah",
        name: "Nasi Kuning Mbah",
        description: "Nasi kuning legendaris dengan resep turun temurun",
        pictureId: "",
        city: "Samarinda",
        rating: 4.7,
        address: "Jl. KS Tubun Gg.7, Samarinda",
        price: "$",
        category: "Indonesia"
    },
    {
        id: "mie-ayam-kacang",
        name: "Mie Ayam Kacang",
        description: "Mie ayam dengan topping kacang khas",
        pictureId: "",
        city: "Samarinda",
        rating: 4.6,
        address: "Jl. Kecapi, Dadi Mulya, Samarinda",
        price: "$",
        category: "Street Food"
    }
];

// DOM Elements
const elements = {
    searchForm: null,
    searchInput: null,
    locationInput: null,
    restaurantGrid: null,
    categoryList: null,
    reviewGrid: null,
    themeToggle: null
};

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    initializeElements();
    setupEventListeners();
    loadRestaurantData();
    loadCategories();
    loadReviews();
    setupDarkMode();
});

// Initialize DOM elements
function initializeElements() {
    elements.searchForm = document.querySelector('#pencarian form');
    elements.searchInput = document.querySelector('#pencarian input[type="text"]');
    elements.locationInput = document.querySelector('#pencarian input[type="text"]:nth-child(2)');
    elements.restaurantGrid = document.querySelector('#rekomendasi-restoran .grid');
    elements.categoryList = document.querySelector('#kategori-populer ul');
    elements.reviewGrid = document.querySelector('#ulasan-terbaru .grid');
    elements.themeToggle = document.querySelector('#theme-toggle');
}

// Setup event listeners
function setupEventListeners() {
    // Search form
    if (elements.searchForm) {
        elements.searchForm.addEventListener('submit', handleSearch);
    }

    // Category filters
    if (elements.categoryList) {
        elements.categoryList.addEventListener('click', handleCategoryFilter);
    }

    // Add theme toggle button if not exists
    addThemeToggle();
}

// Add theme toggle button to header
function addThemeToggle() {
    const themeToggle = document.createElement('button');
    themeToggle.id = 'theme-toggle';
    // themeToggle.innerHTML = '🌙 Dark Mode';  
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

// Handle search form submission
function handleSearch(event) {
    event.preventDefault();
    const searchTerm = elements.searchInput.value.toLowerCase();
    const locationTerm = elements.locationInput.value.toLowerCase();
    
    // Filter restaurants based on search
    const filteredRestaurants = localRestaurants.filter(restaurant => {
        const matchesSearch = restaurant.name.toLowerCase().includes(searchTerm) ||
                            restaurant.description.toLowerCase().includes(searchTerm) ||
                            restaurant.category.toLowerCase().includes(searchTerm);
        const matchesLocation = restaurant.address.toLowerCase().includes(locationTerm) ||
                              restaurant.city.toLowerCase().includes(locationTerm);
        
        return matchesSearch && matchesLocation;
    });
    
    displayRestaurants(filteredRestaurants);
    
    // Show search results message
    if (searchTerm || locationTerm) {
        showNotification(`Menampilkan ${filteredRestaurants.length} hasil pencarian`);
    }
}

// Handle category filter
function handleCategoryFilter(event) {
    event.preventDefault();
    if (event.target.tagName === 'A') {
        // Mapping kategori populer ke kategori data
        const categoryMap = {
            'makanan indonesia': 'indonesia',
            'camilan': 'camilan',
            'makanan jepang': 'jepang',
            'makanan korea': 'korea',
            'kafe': 'kafe',
            'street food': 'street food'
        };
        const displayCategory = event.target.textContent.trim().toLowerCase();
        const dataCategory = categoryMap[displayCategory] || displayCategory;

        // Remove active class from all categories
        document.querySelectorAll('#kategori-populer li').forEach(li => {
            li.classList.remove('active');
        });

        // Add active class to clicked category
        event.target.parentElement.classList.add('active');

        // Filter restaurants by category
        const filteredRestaurants = localRestaurants.filter(restaurant => 
            restaurant.category.toLowerCase() === dataCategory
        );

        displayRestaurants(filteredRestaurants);
        showNotification(`Menampilkan restoran kategori: ${event.target.textContent}`);
    }
}

// Load restaurant data (using local data as fallback)
async function loadRestaurantData() {
    try {
        // Try to fetch from external API first
        const response = await fetch('https://restaurant-api.dicoding.dev/list');
        
        if (response.ok) {
            const data = await response.json();
            displayRestaurants(data.restaurants);
        } else {
            throw new Error('API not available');
        }
    } catch (error) {
        console.log('Using local restaurant data:', error.message);
        // Use local data if API fails
        displayRestaurants(localRestaurants);
    }
}

// Display restaurants in grid
function displayRestaurants(restaurants) {
    if (!elements.restaurantGrid) return;
    
    elements.restaurantGrid.innerHTML = '';
    
    restaurants.forEach(restaurant => {
        const restaurantCard = createRestaurantCard(restaurant);
        elements.restaurantGrid.appendChild(restaurantCard);
    });
}

// Create restaurant card element
function createRestaurantCard(restaurant) {
    const article = document.createElement('article');
    article.className = 'card';
    article.innerHTML = `
        <h3>${restaurant.name}</h3>
        <p class="address">${restaurant.address || restaurant.city}</p>
        <p class="category">${restaurant.category || 'Various'} • ${restaurant.price || '$$'}</p>
        <p class="rating">Rating: ${restaurant.rating}/5</p>
        <button class="detail-btn" data-id="${restaurant.id}">Lihat Detail</button>
    `;
    
    // Add click event to detail button
    const detailBtn = article.querySelector('.detail-btn');
    detailBtn.addEventListener('click', () => showRestaurantDetail(restaurant));
    
    return article;
}

// Load categories
function loadCategories() {
    const categories = [
        'Makanan Indonesia', 'Camilan', 'Makanan Jepang', 
        'Makanan Korea', 'Kafe', 'Street Food'
    ];
    
    if (!elements.categoryList) return;
    
    elements.categoryList.innerHTML = '';
    
    categories.forEach(category => {
        const li = document.createElement('li');
        li.innerHTML = `<a href="#${category.toLowerCase().replace(' ', '-')}">${category}</a>`;
        elements.categoryList.appendChild(li);
    });
}

// Load reviews
function loadReviews() {
    const reviews = [
        {
            restaurant: "Mie Ayam Kacang",
            reviewer: "Budi Santoso",
            rating: 4,
            comment: "Porsinya sangat besar, ayamnya juga banyak, harganya juga sangat murah. Cuma sedikit belum terbiasa buat makan mie ayam tanpa keripiknya yang diganti kacang.",
            date: "2025-08-25"
        },
        {
            restaurant: "Kikitaru (Ex. Taberu)",
            reviewer: "Sari Wijaya",
            rating: 5,
            comment: "Tempatnya sangat mirip seperti di jepang, ramen nya juga ga kalah enak sama resto sebelah.",
            date: "2025-08-24"
        }
    ];
    
    if (!elements.reviewGrid) return;
    
    elements.reviewGrid.innerHTML = '';
    
    reviews.forEach(review => {
        const reviewCard = createReviewCard(review);
        elements.reviewGrid.appendChild(reviewCard);
    });
}

// Create review card element
function createReviewCard(review) {
    const article = document.createElement('article');
    article.className = 'card';
    article.innerHTML = `
        <h3>${review.restaurant}</h3>
        <p class="reviewer">Oleh: ${review.reviewer}</p>
        <p class="rating">Rating: ${'⭐'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</p>
        <p class="comment">${review.comment}</p>
        <time datetime="${review.date}">${formatDate(review.date)}</time>
    `;
    
    return article;
}

// Show restaurant detail (modal/popup)
function showRestaurantDetail(restaurant) {
    // Create modal element
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    `;
    
    modal.innerHTML = `
        <div class="modal-content" style="
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--border-radius);
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        ">
            <h2>${restaurant.name}</h2>
            <p><strong>Alamat:</strong> ${restaurant.address || restaurant.city}</p>
            <p><strong>Kategori:</strong> ${restaurant.category || 'Various'}</p>
            <p><strong>Rating:</strong> ${restaurant.rating}/5</p>
            <p><strong>Harga:</strong> ${restaurant.price || '$$'}</p>
            ${restaurant.description ? `<p><strong>Deskripsi:</strong> ${restaurant.description}</p>` : ''}
            <button id="close-modal" style="
                margin-top: 1rem;
                padding: 0.5rem 1rem;
                background: var(--primary-color);
                color: white;
                border: none;
                font-family: 'Chela One', system-ui;
                letter-spacing: 0.1rem;
                font-size: 1rem;
                border-radius: 8px;
                cursor: pointer;
            ">Tutup</button>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal event
    modal.querySelector('#close-modal').addEventListener('click', () => {
        document.body.removeChild(modal);
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
}

// Dark mode functionality
function setupDarkMode() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        enableDarkMode();
    }
}

function toggleDarkMode() {
    const isDarkMode = document.body.classList.contains('dark-mode');
    
    if (isDarkMode) {
        disableDarkMode();
    } else {
        enableDarkMode();
    }
}

function enableDarkMode() {
    document.body.classList.add('dark-mode');
    localStorage.setItem('darkMode', 'true');
}

function disableDarkMode() {
    document.body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', 'false');
}

// Utility functions
function showNotification(message) {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--accent-color);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1001;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .dark-mode {
        --background-color: #1a1a1a;
        --card-bg: #2d2d2d;
        --text-color: #ffffff;
        --muted-color: #aaaaaa;
        --header-footer-bg: #2d2d2d;
        --header-footer-text: #ffffff;
    }
    
    .dark-mode header,
    .dark-mode footer {
        background: var(--header-footer-bg);
    }
    
    .dark-mode .card {
        border-color: #444;
    }
    
    #kategori-populer a.active {
        background: var(--primary-color);
        color: white;
    }
    
    .detail-btn {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background var(--transition);
    }
    
    .detail-btn:hover {
        background: var(--primary-color);
    }
`;
document.head.appendChild(style);