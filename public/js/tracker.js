let activityInterval = null;
let trackerIsRunning = false;


function startActivityTracker() {
    if(!trackerIsRunning){
        activityInterval = setInterval(() => {
            updateActivity();
        }, 5000);   
        trackerIsRunning = true;
    }
}

function stopActivityTracker() {
    if(trackerIsRunning){
        clearInterval(activityInterval);
        trackerIsRunning = false;
    }
}

function updateActivity(){
    $.ajax({
        url: 'activity-tracker/update',
        type: "GET",
        success: function(response) {
            if(response['is_checked_in']){
                startActivityTracker();
            }else{
                stopActivityTracker();
            }
        },
    });
}

// Start the interval when the page is loaded
window.addEventListener('load', () => {
    updateActivity();
  });


// var inactivityThreshold = 5 * 60 * 1000; // 5 minutes (adjust as needed)
// var lastActivityTimestamp = new Date().getTime();

// function resetActivityTimer() {
//     lastActivityTimestamp = new Date().getTime();
// }

// function checkInactivity() {
//     var currentTime = new Date().getTime();
//     var elapsedTime = currentTime - lastActivityTimestamp;
    
//     if (elapsedTime >= inactivityThreshold) {
//         // User has been inactive for the specified duration
//         // Send an AJAX request to the server to update the activity status
//         $.ajax({
//             url: '/update-activity-status',
//             method: 'POST',
//             data: { activityStatus: 'inactive' },
//             success: function(response) {
//                 // Handle the server response if needed
//             },
//             error: function(xhr, status, error) {
//                 // Handle the AJAX error if needed
//             }
//         });
//     }
// }

// // Event listeners to capture user activity
// $(document).mousemove(resetActivityTimer);
// $(document).keydown(resetActivityTimer);

// // Periodically check for inactivity
// setInterval(checkInactivity, 5000); // Check every 1 minute
