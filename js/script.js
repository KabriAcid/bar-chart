// Function to start count up animation
function startCountUp(id) {
    const element = document.getElementById(id);
    const countTo = element.getAttribute("countTo");
    const countUp = new CountUp(id, countTo);
    if (!countUp.error) {
        countUp.start();
    } else {
        console.error(countUp.error);
    }
}

// Start count up for each section
['total-students', 'total-teachers', 'total-alumni', 'total-applicants'].forEach(startCountUp);

// Set bar heights dynamically based on the maximum value
function setBarHeights() {
    const maxTotal = Math.max(...Object.values(totals));
    const barContainerHeight = document.querySelector('.bar-container').offsetHeight;

    // Scale bars relative to the maximum value
    document.getElementById('bar-students').style.height = (totals.students / maxTotal) * barContainerHeight + 'px';
    document.getElementById('bar-teachers').style.height = (totals.teachers / maxTotal) * barContainerHeight + 'px';
    document.getElementById('bar-alumni').style.height = (totals.alumni / maxTotal) * barContainerHeight + 'px';
    document.getElementById('bar-applicants').style.height = (totals.applicants / maxTotal) * barContainerHeight + 'px';
}

// Call function to set bar heights
setBarHeights();
