<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAR RENTAL IN RURAL AREAS</title>
    <!-- <link rel="stylesheet" href="../css/home.css"> -->
     <style>
        body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F4FDFF; /* Light background for the body */
}

main {
    padding: 2rem;
}

.home-container {
    margin-top: 25px;
    display: flex; /* Arrange content and image side-by-side */
    align-items: center; 
    min-height: 100vh; /* Ensure the page takes up full viewport height */
    background: url('../pictures/bg.webp') no-repeat center center; /* Set the background image */
    background-size: cover; /* Ensure the background covers the entire area */
    position: relative; /* Positioning context for overlay */
}

.home-content {
    flex: 0 0 50%;  /* Set width to 50% of the container */
    padding: 80px; 
    position: relative; /* Positioning context for z-index */
    z-index: 1; /* Ensure content is above the background */
}

.home-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(24, 27, 38, 0.5); /* Dark overlay with new color */
    z-index: -1; /* Position behind the text */
}
.home-content h1 {
    font-size: 3rem; /* Adjust font size as needed */
    color: #D5DFF2; /* Updated heading color */
    margin-bottom: 20px;
}

.home-content p {
    line-height: 1.6;
    color: #D5DFF2; /* Updated paragraph text color */
    font-size: 18px;
}

.highlight {
    font-weight: bold;
    color: #9FA7BF; /* Highlight color */
}

/* Button Styling */
.request-quote {
    background-color: #D5DFF2; /* Bright button color updated */
    text-decoration: none;
    color: #181b26; /* Dark text color on button */
    border: none;
    padding: 15px 30px;
    font-size: 1.2em;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.request-quote:hover {
    background-color: #4F5576; /* Darker shade on hover */
}

.tag {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.8em;
    text-transform: uppercase;
    margin-bottom: 10px;
    background-color: #4F5576; /* Tag background color updated */
    color: #181b26; /* Tag text color updated */
}

.popular {
    background-color: #4F5576; 
    color: white;
    text-align: center;
    font-size: 2rem;
}

/* Image Styling */
.image {
    text-align: center;
    flex: 1.5; /* Give more space to the image */
}

.image img {
    height: auto; 
    width: 800px; /* Make the image take full width */
    max-height: 700px; /* Limit the height to avoid overflow */
    border: 5px solid #D98B48; /* Add a solid border with a color */
    border-radius: 8px; /* Optional: rounded corners for the border */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Add shadow for depth */
    transition: transform 0.3s; /* Smooth transition for hover effect */
}

.image img:hover {
    transform: scale(1.05); /* Slightly enlarge the image on hover */
}

/* Remove bullets from unordered list */
ul li {
    list-style-type: none; /* Removes the bullets */
    padding: 0; /* Removes default padding */
    margin: 0; /* Removes default margin */
}

/* Remove underline from all links */
a {
    text-decoration: none; /* Remove underline from all links */
}

.info-section {
    display: flex; /* Use Flexbox for alignment */
    justify-content: center; /* Center items horizontally */
    align-items: flex-start; /* Align items to the top */
    margin: 20px; /* Margin around the section */
    flex-wrap: wrap; /* Allow wrapping to the next line on smaller screens */
}

/* Info Container */
.info-container {
    background-color: #F4FDFF; /* Light background for the container */
    padding:  20px; /* Padding around the content */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    margin: 15px; /* Space around each container */
    flex: 1 1 30%; /* Allow containers to grow and shrink, with a base width of 30% */
    max-width: 30%; /* Set maximum width for each container */
    transition: transform 0.3s; /* Smooth hover effect */
}

/* Hover Effect */
.info-container:hover {
    transform: translateY(-5px); /* Lift effect on hover */
}

/* Heading styles */
.info-container h2 {
    font-size: 1.8rem; /* Font size for headings */
    color: #181b26; /* Updated dark color for headings */
    margin-bottom: 15px; /* Space below headings */
    border-bottom: 2px solid #D5DFF2; /* Underline effect with accent color */
    padding-bottom: 5px; /* Padding below the heading */
}

/* Paragraph styles */
.info-container p {
    line-height: 1.6; /* Line height for better readability */
    color: #555; /* Darker color for paragraphs */
    margin-bottom: 15px; /* Space below paragraphs */
}

/* List styles */
.info-container ul {
    padding-left: 20px; /* Indent the list */
    margin-bottom: 20px; /* Space below lists */
}

.info-container li {
    margin-bottom: 10px; /* Space between list items */
    color: #555; /* Darker color for list items */
    position: relative; /* Position for pseudo-element */
}

/* List Item Bullet Style */
.info-container li::before {
    content: '✔'; /* Custom bullet point */
    color: #D5DFF2; /* Accent color for bullet */
    position: absolute; /* Positioning */
    left: -20px; /* Position to the left of the text */
}

/* Responsive Styles */
@media (max-width: 768px) {
    .info-container {
        flex: 1 1 100%; /* Full width on smaller screens */
        max-width: 100%; /* Remove max-width constraint */
    }
}
     </style>
</head>
<body>
    <?php include('../header/header.php');// include the header nav?>
    <div class="home-container">
        <div class="home-content">
            <div class="tag popular">Toyota Cars</div>
            <h1>Rent A Car In <br> Rural Areas </h1>
            <p>Experience exceptional value-for-money <span class="highlight">car rental services in Rural Areas</span> and across all provinces in the Philippines. With a proven track record of service excellence, we cater to all your car rental needs in Manila and beyond.</p>
            <a href="reservation.php" class="request-quote"> Request Quote </a>
        </div>
    </div>
    <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
    
</body>
</html>