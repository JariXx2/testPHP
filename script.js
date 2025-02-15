// Getting DOM elements by their ID
const userForm = document.getElementById("user-form"); 
const dataTable = document.getElementById("data");   
const table = document.getElementById("table");      
const userHeader = document.getElementById("user-header"); 

// Handler for the form submission event (user selection)
userForm.onsubmit = function (e) {
    e.preventDefault(); // Preventing the standard behavior of the form (page reloading)

    // Getting user data from the drop-down list
    const userId = document.getElementById("user").value;
    const userName = document.getElementById("user").options[document.getElementById("user").selectedIndex].text;

    // Executing an AJAX request to data.php to get transaction data
    fetch(`data.php?user=${userId}&userName=${encodeURIComponent(userName)}`)
    .then((response) => {
        // Checking whether the request was successfully completed
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`); 
        }
        return response.json();
    })
    .then((data) => {
        // Getting the body of the table
        const tableBody = table.querySelector("tbody");
        
        // Cleaning the table
        tableBody.innerHTML = ""; 

        // We sort through the received data and add rows to the table
        data.forEach((item) => {
            const row = tableBody.insertRow();       
            const monthCell = row.insertCell();    
            const amountCell = row.insertCell();   

            monthCell.textContent = item.month;  
            amountCell.textContent = item.balance; 
        });

        // Updating the table header with the name of the selected user
        userHeader.textContent = `Transactions of ${userName}`;
        
        // Displaying the table container, making the table visible
        dataTable.style.display = "block";
    })
    .catch((error) => console.error("Ошибка:", error)); // Handling possible errors when executing the request
};
