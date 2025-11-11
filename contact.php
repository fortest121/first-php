<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum Access Protocol - Compact View v3.0</title>
    <!-- Using CDN for simplicity -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Customizing Tailwind config for a darker theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#1a1a2e',
                        'card-bg': '#1f283d',
                        'accent': '#66fcf1',
                        'secondary-accent': '#45a29e',
                        'text-light': '#c5c6c7',
                        'field-bg': '#313a4b',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom CSS to ensure no scrolling */
        .no-scroll-container {
            min-height: 100vh;
            padding: 1rem;
            box-sizing: border-box;
        }
        /* Simple CSS for the 'shake' effect */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        .shake-animation {
            animation: shake 0.5s;
        }
        /* Hide default number input arrows/spinners */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield; /* Firefox */
        }
    </style>
</head>

<body class="bg-dark-bg text-text-light flex items-center justify-center no-scroll-container">

    <!-- Increased max-w for a wider, shorter look (max-w-6xl) -->
    <div class="bg-card-bg p-8 rounded-xl shadow-2xl w-full max-w-6xl border border-secondary-accent/20">
        
        <!-- Header & Price/Action Segment (Condensed) -->
        <header class="flex justify-between items-center pb-4 mb-4 border-b border-secondary-accent/30">
            <!-- Title -->
            <div class="flex-1">
                <h1 class="text-3xl font-extrabold text-accent tracking-wider uppercase">
                    <span class="text-secondary-accent">Quantum</span> Access Deployment
                </h1>
                <p class="text-sm text-text-light/70 mt-1">Initiate system enrollment and finalize fiscal commitment.</p>
            </div>

            <!-- Price & Button (Integrated into header) -->
            <div class="flex items-center space-x-6">
                <div class="text-right">
                    <p class="text-sm font-medium text-text-light/60 uppercase">System Cost</p>
                    <div class="flex items-center justify-end space-x-2 mt-0.5">
                        <p class="text-2xl font-bold text-accent">₹7450</p>
                        <p class="text-md text-text-light/50 line-through">₹8000</p>
                    </div>
                </div>
                <button
                    class="bg-accent text-dark-bg font-bold py-2 px-6 rounded-full shadow-lg hover:bg-secondary-accent transition duration-300 transform hover:scale-[1.05] text-lg uppercase tracking-wide"
                    id="select-plan-button">
                    Authorize Access
                </button>
            </div>
        </header>

        <!-- User Information Form (Maximized Width, Reduced Vertical Space) -->
        <form action="#" method="POST" class="space-y-4">
            
            <!-- Three-Column Layout for compactness (Personal Data) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-medium text-text-light mb-1">👤 Full Name / Entity</label>
                    <input type="text" id="name" name="name" placeholder="Agent Name" required
                        class="block w-full px-4 py-2 border border-field-bg/50 bg-field-bg rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-300 placeholder-text-light/40 text-sm">
                </div>
                <!-- Phone (Numeric Only, 10 digits) -->
                <div>
                    <label for="phone" class="block text-xs font-medium text-text-light mb-1">📞 Contact Protocol ID <span class="text-red-400">(Numeric Only)</span></label>
                    <input type="number" id="phone" name="phone" placeholder="Secure Contact Number (10 digits)" required
                        pattern="[0-9]{10}" 
                        title="Enter a 10 digit number"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
                        class="block w-full px-4 py-2 border border-field-bg/50 bg-field-bg rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-300 placeholder-text-light/40 text-sm">
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-medium text-text-light mb-1">📧 Email Nexus Point</label>
                    <input type="email" id="email" name="email" placeholder="Secure Email Address" required
                        class="block w-full px-4 py-2 border border-field-bg/50 bg-field-bg rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-300 placeholder-text-light/40 text-sm">
                </div>
            </div>

            <!-- Two-Column Layout (Plan/Location) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- PLAN SELECTION -->
                <div>
                    <label for="plan" class="block text-xs font-medium text-text-light mb-1">⭐ Access Plan Tier</label>
                    <div class="relative">
                        <select id="plan" name="plan" required
                            class="block w-full px-4 py-2 border border-field-bg/50 bg-field-bg rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-300 text-sm cursor-pointer">
                            <option value="" disabled selected class="bg-card-bg">Select Access Tier</option>
                            <option value="Basic" class="bg-card-bg">Basic (Standard Protocol)</option>
                            <option value="Standard" class="bg-card-bg">Standard (Accelerated Data Flow)</option>
                            <option value="Premium" class="bg-card-bg">Premium (Quantum Encryption)</option>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-accent">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- State Selection -->
                <div>
                    <label for="state" class="block text-xs font-medium text-text-light mb-1">🗺️ Regional Deployment Context</label>
                    <div class="relative">
                        <select id="state" name="state" required
                            class="block w-full px-4 py-2 border border-field-bg/50 bg-field-bg rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-300 text-sm cursor-pointer">
                            <!-- Options will be generated by the JavaScript loop -->
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-accent">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button (Centered) -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-secondary-accent text-dark-bg font-bold py-3 rounded-lg shadow-xl hover:bg-accent transition duration-500 text-lg tracking-widest uppercase border border-secondary-accent/50 group relative overflow-hidden">
                    <span class="absolute right-0 top-0 h-full w-0 transition-all duration-300 group-hover:w-full bg-white/20"></span>
                    <span class="relative z-10">Execute Plan Deployment</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Enhanced JavaScript for Form Submission Feedback and Dynamic Content -->
    <script>
        const indianStatesAndUTs = [
            "Select Operational Region",
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", 
            "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", 
            "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", 
            "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", 
            "Telangana", "Tripura", "Uttar Pradesh", "Uttarakhand", "West Bengal",
            // Union Territories
            "Andaman and Nicobar Islands", "Chandigarh", "Dadra and Nagar Haveli and Daman and Diu", 
            "Delhi", "Jammu and Kashmir", "Ladakh", "Lakshadweep", "Puducherry"
        ];

        // 1. Dynamic State Dropdown Generation (Loop Implementation)
        const stateSelect = document.getElementById('state');
        indianStatesAndUTs.forEach((state, index) => {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            option.classList.add('bg-card-bg');
            if (index === 0) {
                option.disabled = true;
                option.selected = true;
            }
            stateSelect.appendChild(option);
        });

        // 2. Form Validation and Submission Logic
        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault(); 

            let isValid = true;
            // Get all required fields, including the new 'plan' select
            let fields = document.querySelectorAll('input[required], select[required]');
            
            fields.forEach(function (field) {
                // Check if the field is empty or still at the default placeholder value
                if (field.value.trim() === '' || field.value === 'Select Operational Region' || field.value === 'Select Access Tier') {
                    isValid = false;
                    field.classList.remove('focus:ring-accent', 'focus:border-accent');
                    field.classList.add('border-red-500', 'ring-2', 'ring-red-500/50');
                } else {
                    field.classList.add('focus:ring-accent', 'focus:border-accent');
                    field.classList.remove('border-red-500', 'ring-2', 'ring-red-500/50');
                }
            });

            // Specific Phone Number Validation (10 digits)
            const phoneField = document.getElementById('phone');
            if (phoneField.value.length !== 10 && isValid) {
                 isValid = false;
                 phoneField.classList.remove('focus:ring-accent', 'focus:border-accent');
                 phoneField.classList.add('border-red-500', 'ring-2', 'ring-red-500/50');
                 // Only show alert for specific phone error if no other field is invalid
                 if (document.querySelectorAll('.border-red-500').length === 1) { 
                    alert('Phone number must be exactly 10 digits.');
                 }
            }

            const submitButton = document.querySelector('button[type="submit"]');

            if (isValid) {
                // Success state simulation
                submitButton.textContent = 'DEPLOYMENT SUCCESSFUL';
                submitButton.classList.remove('bg-secondary-accent', 'hover:bg-accent', 'bg-red-600', 'shake-animation');
                submitButton.classList.add('bg-green-600', 'hover:bg-green-700', 'animate-pulse');

                setTimeout(() => {
                    alert('ACCESS GRANTED: Protocol deployment is complete. Redirecting to Payment Nexus...');
                }, 1000); 

            } else {
                // Error state simulation
                submitButton.textContent = 'VALIDATION ERROR - REVIEW FIELDS';
                submitButton.classList.remove('bg-secondary-accent', 'hover:bg-accent', 'bg-green-600', 'animate-pulse');
                submitButton.classList.add('bg-red-600', 'shake-animation'); 

                setTimeout(() => {
                    submitButton.textContent = 'Execute Plan Deployment';
                    submitButton.classList.remove('bg-red-600', 'shake-animation');
                    submitButton.classList.add('bg-secondary-accent', 'hover:bg-accent');
                }, 2000);
            }
        });

        // Auto-select "Authorize Access" on button click to proceed to form
        document.getElementById('select-plan-button').addEventListener('click', function() {
            document.getElementById('name').focus();
        });
    </script>

</body>

</html>