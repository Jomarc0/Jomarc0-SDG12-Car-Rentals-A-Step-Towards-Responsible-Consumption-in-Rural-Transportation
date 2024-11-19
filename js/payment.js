function handleNext() {
    const mobileNumber = document.getElementById('gcash-account').value;

    if (!mobileNumber || !/^(\+639)\d{9}$/.test(mobileNumber)) {
        alert("Please enter a valid GCash mobile number.");
        return;
    }

    document.getElementById("payment-content").classList.add("hidden");
    document.getElementById("top-content").classList.add("hidden");
    document.getElementById("payment-section").classList.remove("hidden");
    document.getElementById("qr-code-container").classList.remove("hidden");
}