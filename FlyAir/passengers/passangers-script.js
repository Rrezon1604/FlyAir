window.onload = function() {
    let passengerNum = 2;

    document.getElementById('addPassengers').addEventListener('click', function() {
        var newPassengerDiv = document.createElement('div');
        newPassengerDiv.className = 'passenger';

        var passengerHeader = document.createElement('h2');
        passengerHeader.textContent = `Passenger #${passengerNum}`;

        newPassengerDiv.innerHTML = `
        <form method="post">
            <label for="">Full Name:</label>
            <input type="text" name="fullname">
            <label for="">Email:</label>
            <input type="email" name="email">
            <label for="">Gender:</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label for="">Personal Number:</label>
            <input type="number" name="personalNumber">
            <label for="">Passport Number:</label>
            <input type="text" name="passportNumber">
            <label for="">Date of Birth:</label>
            <input type="date" name="dateOfBirth">
            <label for="">Class:</label>
            <select name="class">
                <option value="Business">Business</option>
                <option value="Economy">Economy (+20€)</option>
                <option value="First">First (+40€)</option>
            </select>
        </form>
        `;

        newPassengerDiv.insertBefore(passengerHeader, newPassengerDiv.firstChild);

        var flightPassengersDiv = document.querySelector('.flightPassengers form');

        var buttonsDiv = document.querySelector('.buttons');
        flightPassengersDiv.insertBefore(newPassengerDiv, buttonsDiv);

        passengerNum++;
    });



    document.getElementById('removePassengers').addEventListener('click', function() {
        var flightPassengersDiv = document.querySelector('.flightPassengers form');
        
        var passengerDivs = flightPassengersDiv.querySelectorAll('.passenger');

        if (passengerDivs.length > 1) {
            var lastPassengerDiv = passengerDivs[passengerDivs.length - 1];
            flightPassengersDiv.removeChild(lastPassengerDiv);
            passengerNum--;
        } else {
            alert('At least one passenger must remain.');
        }
    });
};
