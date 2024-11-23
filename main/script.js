const formSteps = document.querySelectorAll('.form-step');
const nextBtns = document.querySelectorAll('.next-btn');
const prevBtns = document.querySelectorAll('.prev-btn');
const submitBtn = document.querySelector('.submit'); 
const formProgress = document.querySelectorAll('.step');

let currentStep = 0;

// Function to validate the current step
function validateCurrentStep() {
    const currentFormStep = formSteps[currentStep];
    const inputs = currentFormStep.querySelectorAll('input, select, textarea');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.checkValidity()) {
            isValid = false;
            input.classList.add('error'); // Add error class for styling
        } else {
            input.classList.remove('error'); // Remove error class if valid
        }
    });

    return isValid;
}

// Next button functionality
nextBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        if (validateCurrentStep()) {
            formSteps[currentStep].classList.remove('active');
            currentStep++;
            formSteps[currentStep].classList.add('active');
            updateFormProgress();
        }
    });
});

// Previous button functionality
prevBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        formSteps[currentStep].classList.remove('active');
        currentStep--;
        formSteps[currentStep].classList.add('active');
        updateFormProgress();
    });
});

// Handle the submit button click
submitBtn.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent actual form submission

    if (validateCurrentStep()) {
        // Here you can gather all the form data and process it
        const formData = new FormData();
        formSteps.forEach(step => {
            const inputs = step.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    formData.append(input.name, input.value);
                }
            });
        });

        // For demonstration, log the form data to the console
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // Move to the "Receipt" step
        formSteps[currentStep].classList.remove('active');
        currentStep++; // Move to the next (Receipt) step
        formSteps[currentStep].classList.add('active');
        updateFormProgress();
    }
});

// Update form progress
function updateFormProgress() {
    formProgress.forEach((step, index) => {
        if (index <= currentStep) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });
}