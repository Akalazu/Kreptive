 <!-- end: Content -->
 </div>
 </section>
 <!-- end: Chat -->

 <script src="script.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js" integrity="sha512-hkvXFLlESjeYENO4CNi69z3A1puvONQV5Uh+G4TUDayZxSLyic5Kba9hhuiNLbHqdnKNMk2PxXKm0v7KDnWkYA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <!--Contact JS -->

 <script>
     console.log("i am here!");

     $('#message_content').emojioneArea();

     // A chat is what is loaded on the left pane, a conversation is what is loaded when the left pane is clicked.

     // Function to create and append a message div when a message is sent
     function sendMessage(messageText) {
         // Get the conversation wrapper element
         const conversationWrapper = document.querySelector(".conversation-wrapper");

         // Create the outer list item
         const messageItem = document.createElement("li");
         messageItem.classList.add("conversation-item");

         // Add the profile image
         const messageSide = document.createElement("div");
         messageSide.classList.add("conversation-item-side");

         const messageImage = document.createElement("img");
         messageImage.classList.add("conversation-item-image");
         messageImage.src = "https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60";
         messageImage.alt = "";

         messageSide.appendChild(messageImage);

         // Create the main content div
         const messageContent = document.createElement("div");
         messageContent.classList.add("conversation-item-content");

         // Create the wrapper for the message box
         const messageWrapper = document.createElement("div");
         messageWrapper.classList.add("conversation-item-wrapper");

         // Create the message box
         const messageBox = document.createElement("div");
         messageBox.classList.add("conversation-item-box");

         // Create the message text div
         const messageTextDiv = document.createElement("div");
         messageTextDiv.classList.add("conversation-item-text");

         // Add the actual message text
         const messageParagraph = document.createElement("p");
         messageParagraph.innerText = messageText;

         // Add the time
         const messageTime = document.createElement("div");
         messageTime.classList.add("conversation-item-time");
         const currentTime = new Date().toLocaleTimeString([], {
             hour: '2-digit',
             minute: '2-digit'
         });
         messageTime.innerText = currentTime;

         // Append elements to form the message structure
         messageTextDiv.appendChild(messageParagraph);
         messageTextDiv.appendChild(messageTime);
         messageBox.appendChild(messageTextDiv);
         messageWrapper.appendChild(messageBox);
         messageContent.appendChild(messageWrapper);

         // Append dropdown options
         const dropdown = document.createElement("div");
         dropdown.classList.add("conversation-item-dropdown");

         const dropdownButton = document.createElement("button");
         dropdownButton.type = "button";
         dropdownButton.classList.add("conversation-item-dropdown-toggle");

         const icon = document.createElement("i");
         icon.classList.add("ri-more-2-line");

         dropdownButton.appendChild(icon);

         const dropdownList = document.createElement("ul");
         dropdownList.classList.add("conversation-item-dropdown-list");

         const forwardOption = document.createElement("li");
         const forwardLink = document.createElement("a");
         forwardLink.href = "#";
         forwardLink.innerHTML = `<i class="ri-share-forward-line"></i> Forward`;
         forwardOption.appendChild(forwardLink);

         const deleteOption = document.createElement("li");
         const deleteLink = document.createElement("a");
         deleteLink.href = "#";
         deleteLink.innerHTML = `<i class="ri-delete-bin-line"></i> Delete`;
         deleteOption.appendChild(deleteLink);

         dropdownList.appendChild(forwardOption);
         dropdownList.appendChild(deleteOption);

         dropdown.appendChild(dropdownButton);
         dropdown.appendChild(dropdownList);

         // Append dropdown to the message box
         messageBox.appendChild(dropdown);

         // Append all parts to the message item
         messageItem.appendChild(messageSide);
         messageItem.appendChild(messageContent);

         // Append the message item to the conversation wrapper
         conversationWrapper.appendChild(messageItem);
     }

     //  Send chat function
     $(document).ready(function() {
         $('#send_button').click(function() {
             // Get values from the form
             let messageContent = $('#message_content').val();
             let senderId = $('#sender_id').val();
             let receiverId = $('#receiver_id').val();
             let chatId = $('#chat_id').val();

             // Check if message is not empty
             if ($.trim(messageContent) != '') {
                 $.ajax({
                     url: 'send_message.php', // PHP file to handle message sending
                     type: 'POST',
                     data: {
                         content: messageContent,
                         sender_id: senderId,
                         receiver_id: receiverId,
                         chat_id: chatId
                     },
                     success: function(response) {
                         let res = JSON.parse(response);
                         if (res.status == 'success') {
                             // Clear the textarea on successful send
                             $('#message_content').val('');
                             $('.emojionearea-editor').html('');
                             // Append the new message to the conversation
                             sendMessage(res.message);
                             scrollToLatestMessage();
                         }

                     },
                     error: function(xhr, status, error) {
                         console.log("Error: " + error);
                     }
                 });
             }
         });
     });

     //  Load chats on the left pane
     $(document).ready(function() {
         // Function to fetch chat data

         function fetchChats() {
             $.ajax({
                 url: 'fetch_chats.php',
                 type: 'GET',
                 data: {
                     userId: <?= $currUser->id ?> //fetch chats for the current user
                 },
                 success: function(data) {
                     // Empty the chat list first
                     $('#chatList').empty();

                     // Loop through each chat and create HTML
                     $('#chatList').html(data);
                 },
                 error: function(xhr, status, error) {
                     console.error("AJAX Error: " + status + error);
                 }
             });
         }

         // Fetch chats on page load
         fetchChats();

          setInterval(fetchChats, 2000);
     });

     // Add event listener for clicking on a chat to load conversations
     $(document).on('click', '.user-chat', function(event) {
         const conversationId = $(this).data('conversation'); // Get conversation ID from data attribute
         const receiverId = $(this).data('receiver'); // Get receiver ID from data attribute
         const senderId = $(this).data('sender'); // Get receiver ID from data attribute
         let isFirstRun = true; // Flag to ensure scrollToLatestMessage only runs once
         $('.conversation-default').removeClass('active');
         $('#conversation-1').addClass('active');

         // Clear any previous interval to prevent multiple requests
         if (typeof fetchInterval !== 'undefined') {
             clearInterval(fetchInterval);
         }

         // AJAX call to fetch the conversation details and messages
         function fetchMessages() {
             $.ajax({
                 url: 'fetch_conversation.php', // PHP file to handle the database request
                 type: 'GET',
                 data: {
                     conversation_id: conversationId,
                     userId: senderId
                 },
                 dataType: 'json',
                 success: function(response) {
                     if (response.success) {
                         const {
                             receiver,
                             messages,
                             my_id
                         } = response;

                         // Update conversation details (receiver info in conversation-top)
                         $('.conversation-user-name').text(`${receiver.first_name} ${receiver.last_name}`);
                         $('.conversation-user-image').attr('src', receiver.image || 'default_image.jpg'); // fallback image if needed
                         $('.conversation-user-status').text(receiver.status || 'Online');

                         // Clear and populate messages
                         const $conversationWrapper = $('.conversation-wrapper');
                         $conversationWrapper.empty();

                         // Batch messages processing and appending to the DOM
                         (async function displayMessages() {
                             const htmlArray = messages.map(message => {
                                 const formattedTime = formatTimeJS(message.time); // Format using JavaScript

                                 const isUserMessage = message.sender_id == my_id;
                                 const messageClass = isUserMessage ? 'conversation-item' : 'conversation-item me';

                                 return `
                                <li class="${messageClass}">
                                    <div class="conversation-item-side">
                                        <img class="conversation-item-image" src="${receiver.image}" alt="user-icon">
                                    </div>
                                    <div class="conversation-item-content">
                                        <div class="conversation-item-wrapper">
                                            <div class="conversation-item-box">
                                                <div class="conversation-item-text">
                                                    <p>${message.content}</p>
                                                    <div class="conversation-item-time">${formattedTime}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>`;
                             });

                             // Append all HTML once to reduce DOM manipulation
                             $conversationWrapper.append(htmlArray.join(''));
                         })();

                         // Set the conversation ID in the hidden input fields
                         $('#chat_id').val(conversationId);
                         $('#sender_id').val(senderId);
                         $('#receiver_id').val(receiverId);

                         // Run scrollToLatestMessage only on the first run
                         if (isFirstRun) {
                             scrollToLatestMessage();
                             isFirstRun = false; // Ensure it only runs once
                         }

                     } else {
                         alert('Failed to load conversation data.');
                     }
                 }
             });
         }

         fetchMessages();
         fetchInterval = setInterval(fetchMessages, 2000);
     });


     // Back button click event
     $(document).on('click', '.conversation-back', function() {
         clearInterval(fetchInterval); // Stop the interval
         $('#conversation-1').removeClass('active');
         $('.conversation-default').addClass('active');
     });

     //  initiate chat on contacts page
     $(document).on('click', '.text-right', async function() {
         const receiverId = $(this).data('id');
         const senderId = <?= $currUser->id ?>;
         const modal = $('#chatModal');
         const closeButton = $('.close-button');
         const chatForm = $('#chatForm');
         const messageField = $('#message');

         try {
             // Initial check if user can message the receiver
             const checkResponse = await $.ajax({
                 url: 'server.php',
                 type: 'POST',
                 data: {
                     receiverId: receiverId
                 }
             });

             if (checkResponse) {
                 // Initiate chat and get conversation ID
                 const initiateResponse = await $.ajax({
                     url: 'server.php',
                     type: 'GET',
                     data: {
                         action: 'initiate_chat'
                     }
                 });

                 const conversationId = initiateResponse; // Use response as conversation ID

                 // Create chat entry
                 const createChatResponse = await $.ajax({
                     url: 'create_chat.php',
                     type: 'POST',
                     data: {
                         sender_id: senderId,
                         receiver_id: receiverId,
                         conversationId: conversationId
                     }
                 });

                 let res = JSON.parse(createChatResponse);
                 if (res.success) {
                     // Open modal and store data attributes for receiverId and chatId
                     modal.show().data('receiverId', receiverId).data('chatId', conversationId);

                     // Handle form submission for sending messages
                     chatForm.off('submit').on('submit', async function(event) {
                         event.preventDefault(); // Prevent default form submission

                         const message = messageField.val().trim();
                         if (message === '') return;

                         try {
                             const sendMessageResponse = await $.ajax({
                                 url: 'server.php',
                                 type: 'POST',
                                 data: {
                                     action: 'send_message',
                                     message: message,
                                     sender_id: senderId,
                                     receiver_id: receiverId,
                                     chatId: conversationId
                                 }
                             });

                             console.log(sendMessageResponse); // Log the response for debugging
                             modal.hide(); // Close modal after sending message
                             messageField.val(''); // Clear the message field
                             window.location.href = './'; // Return to chat window
                         } catch (error) {
                             console.error('Error sending message:', error); // Handle error
                         }
                     });

                 } else if (res.success === 'falsee') {
                     window.location.href = './';
                 } else {
                     alert('Failed to create a new chat, please try again');
                 }

             } else {
                 alert('Sorry! You can only message verified participants');
             }

         } catch (error) {
             console.error("Error initiating chat:", error);
         }

         // Prevent modal from closing when clicking outside of it
         modal.on('click', function(event) {
             if ($(event.target).is('#chatModal')) {
                 event.stopPropagation();
             }
         });

         // Close modal when the close button is clicked
         closeButton.off('click').on('click', function() {
             modal.hide();
             messageField.val(''); // Clear the message field
         });

         // Enable or disable submit button based on textarea input
         messageField.off('input').on('input', function() {
             chatForm.find('button').prop('disabled', !$(this).val().trim());
         });
     });


     function scrollToLatestMessage() {
         const conversationWrapper = document.querySelector('.conversation-main');
         if (conversationWrapper) {
             conversationWrapper.scrollTop = conversationWrapper.scrollHeight;
         }
     }


     // Mark messages as read on the server
     function clearUnreadCount(conversationId, userId) {
         $.ajax({
             url: 'mark_messages_as_read.php',
             type: 'POST',
             data: {
                 conversation_id: conversationId,
                 user_id: userId
             },
             success: function(response) {
                 if (response.success) {
                     console.log("All messages are now read");
                 }
             }
         });
     }

     // Utility function to format time in JavaScript
     function formatTimeJS(datetime) {
         const date = new Date(datetime);
         const hours = date.getHours() % 12 || 12;
         const minutes = date.getMinutes().toString().padStart(2, '0');
         const ampm = date.getHours() >= 12 ? 'pm' : 'am';
         const formattedDate = `${hours}:${minutes}${ampm} ${date.getDate()}-${date.getMonth() + 1}-${date.getFullYear().toString().slice(-2)}`;
         return formattedDate;
     }
 </script>
 </body>

 </html>