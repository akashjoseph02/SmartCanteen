const chatbox = document.getElementById('chatbox');
const toggleChatButton = document.getElementById('toggleChatButton');
const messagesDiv = document.getElementById('messages');
const userInput = document.getElementById('userInput');
const sendButton = document.getElementById('sendButton');

// Define questions that should have buttons
const buttonQuestions = [
    "Hi",
    "What can I order from CafeMingos",
    "What can I order from SurfandTurf",
];

const predefinedResponses = {

    "Hi": {
        response: "Hello! How can I assist you today? You can ask about our different sections and trending items.",
        suggestions: [
            "Beverages",
            "Snacks",
            "Breakfast",
            "Dinner",
            "Lunch",
        ]
    },

    "What can I order from Mingos": {
        response: "Mingos includes a variety of food items. Would you like more details?",
        suggestions: ["Beverages","Snacks"]
    },
    "Snacks": {
        response: "Trending snacks at CafeMingos are Burgers and Sandwitch",
        suggestions: ["Beverages"]
    },
    "Beverages": {
        response: "CafeMingos Beverages includes popular items like Coffee and Tea.",
        suggestions: ["Snacks"]
    },
    "What can I order from SurfandTurf": {
        response: "SurfandTurf includes a variety of food items. Would you like more details?",
        suggestions: [ "Breakfast", "Lunch", "Dinner" ]
    },
    "Breakfast": {
        response: "For breakfast at SurfandTurf, you can try Pancakes or an Omelette.",
        suggestions: [ "Lunch", "Dinner"]
    },

    "Dinner": {
        response: "For dinner at SurfandTurf, you can enjoy Maggie or Pasta. Let me know if you need any more information!",
        suggestions: ["Breakfast", "Lunch"]
    },
    "Lunch": {
        response: "Popular lunch items at SurfandTurf include chicken biryani and chicken fried rice. Let me know if you need any more information!",
        suggestions: ["Breakfast", "Dinner"]
    },

    "what is the trending item today": {
        response: "Chicken Biryani",
        suggestions: []
    },
    "where i can find good milk shakes": {
        response: "You can have a good milkshakes from CafeMingos",
        suggestions: []
    },
    "what is the rate of chhicken meals": {
        response: "The rate of chicken meals is Rs 120",
        suggestions: []
    },
    "where do i get veg foods": {
        response: "Veg foods are available at SurfandTurf",
        suggestions: []
    },
    "suggest some food for breakfast": {
        response: "You can have Masala Dosa with Coffee, Puttu Kadala with Tea and Bread Omlete",
        suggestions: []
    },
    "what is the cost of cold coffee at mingos": {
        response: "40 Rupees",
        suggestions: []
    },

};

function displayMessage(message, sender, suggestions = [], delay = 0) {
    setTimeout(() => {
        const messageDiv = document.createElement('div');
        messageDiv.className = sender === 'user' ? 'userMessage' : 'botMessage';
        messageDiv.innerHTML = `<div>${message}</div>`;

        // Append message to chat
        messagesDiv.appendChild(messageDiv);

        // Append suggestion buttons if there are any
        if (sender === 'bot' && suggestions.length > 0) {
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'buttonContainer';
            suggestions.forEach(suggestion => {
                const button = document.createElement('div');
                button.textContent = suggestion;
                button.className = 'questionButton';
                button.addEventListener('click', () => {
                    handleUserMessage(suggestion); // Handle button click
                });
                buttonContainer.appendChild(button);
            });
            messagesDiv.appendChild(buttonContainer);
        }

        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }, delay); // Delay before displaying the message
}

function getBotResponse(userMessage) {
    return predefinedResponses[userMessage] || { response: "Sorry, I didn't understand that. Please ask about specific food items or sections.",
    suggestions: [
        "Beverages",
        "Snacks",
        "Breakfast",
        "Dinner",
        "Lunch",
    ] };
}

function handleUserMessage(userMessage) {
    if (userMessage) {
        displayMessage(userMessage, 'user');
        const botResponse = getBotResponse(userMessage);
        // Delay the bot response
        displayMessage(botResponse.response, 'bot', botResponse.suggestions, 1000); // 1000 ms = 1 second
        userInput.value = '';
    }
}

function displayInitialButtons() {
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'buttonContainer';
    buttonQuestions.forEach(question => {
        const button = document.createElement('div');
        button.textContent = question;
        button.className = 'questionButton';
        button.addEventListener('click', () => {
            handleUserMessage(question); // Handle button click
        });
        buttonContainer.appendChild(button);
    });
    messagesDiv.appendChild(buttonContainer);
}

// Toggle chatbox visibility on clicking the button
toggleChatButton.addEventListener('click', () => {
    if (chatbox.style.display === 'none' || chatbox.style.display === '') {
        chatbox.style.display = 'flex';
        // Clear previous messages and display welcome message immediately
        messagesDiv.innerHTML = '';
        displayMessage("Welcome! How can I help you today? Are any of these questions what you're looking for?", 'bot', [], 200); // No delay for opening the chatbox
        // Display predefined buttons after the welcome message
        setTimeout(displayInitialButtons, 500); // Delay to ensure the welcome message is visible before buttons
    } else {
        chatbox.style.display = 'none';
    }
});

sendButton.addEventListener('click', () => {
    const userMessage = userInput.value;
    handleUserMessage(userMessage);
});

userInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendButton.click();
    }
});
