$(document).ready(function() {
    $("#editProfileBtn").click(function() {
        $("#editProfileForm").slideToggle();
    });
});

// JavaScript function to show the selected table and hide others
function showTable(tableId) {
    // List of all table IDs
    const tableIds = ['usersTable', 'productsTable', 'transactionsTable'];

    // Hide all tables first
    tableIds.forEach(id => {
        document.getElementById(id).style.display = 'none';
    });

    // Show the selected table
    document.getElementById(tableId).style.display = 'block';
}