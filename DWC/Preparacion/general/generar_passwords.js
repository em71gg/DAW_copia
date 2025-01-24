function generatePassword() {
    const minLength = 15;
    const maxLength = 87;

    // Define character sets
    const lowerChars = "abcdefghijklmnopqrstuvwxyz";
    const upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const digits = "0123456789";
    const specialChars = "!@#$%^&*()-_=+[]{}|;:'\",.<>?/`~";
    const allChars = lowerChars + upperChars + digits + specialChars;

    // Determine random length between minLength and maxLength
    const passwordLength = Math.floor(Math.random() * (maxLength - minLength + 1)) + minLength;

    let password = "";

    // Ensure the password contains at least one character from each required set
    password += upperChars[Math.floor(Math.random() * upperChars.length)];
    password += digits[Math.floor(Math.random() * digits.length)];
    password += specialChars[Math.floor(Math.random() * specialChars.length)];

    // Fill the rest of the password with random characters
    for (let i = password.length; i < passwordLength; i++) {
        password += allChars[Math.floor(Math.random() * allChars.length)];
    }

    // Shuffle the password to ensure randomness
    password = password.split('').sort(() => 0.5 - Math.random()).join('');

    return password;
}

// Generate multiple unique passwords
const numberOfPasswords = 5; // Adjust this to generate more or fewer passwords
const generatedPasswords = new Set();

while (generatedPasswords.size < numberOfPasswords) {
    generatedPasswords.add(generatePassword());
}

// Output the generated passwords
console.log([...generatedPasswords]);
