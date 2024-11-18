<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="tailwindcss-colors.css" />
    <link rel="stylesheet" href="style.css" />
    <!-- Emoji CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" integrity="sha512-vEia6TQGr3FqC6h55/NdU3QSM5XR6HSl5fW71QTKrgeER98LIMGwymBVM867C1XHIkYD9nMTfWK2A0xcodKHNA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <title>Chat</title>
</head>
<style>
    .emojionearea .emojionearea-editor {
        min-height: 50px !important;
    }
</style>

<body>

    <!-- start: Chat -->
    <section class="chat-section">
        <div class="chat-container position-relative overflow-auto">
            <!-- start: Sidebar -->
            <aside class="chat-sidebar">
                <a href="#" class="chat-sidebar-logo">
                    <i class="ri-chat-1-fill"></i>
                </a>
                <ul class="chat-sidebar-menu">
                    <li>
                        <a href="../" data-title="Portal"><i class="ri-home-3-line"></i>
                            <span class="icon-title">Home</span>
                        </a>
                    </li>
                    <li class="<?= checkActiveState('contacts') ?>">
                        <a href="contacts" data-title="Chats"><i class="ri-chat-3-line"></i>

                            <span class="icon-title">Chats</span>
                        </a>

                    </li>
                    <li class="<?= checkActiveState('index') ?>">
                        <a href="./" data-title="Community"><i class="ri-contacts-line"></i>
                            <span class="icon-title">Members</span>
                        </a>

                    </li>

                    <li>
                        <a href="../account" data-title="Settings"><i class="ri-user-line"></i>

                            <span class="icon-title">Profile</span>
                        </a>

                    </li>
                    <li class="chat-sidebar-profile">
                        <button type="button" class="chat-sidebar-profile-toggle">
                            <img
                                src="<?= $currUser->image ?>"
                                alt="user_img" class="user_img" />
                        </button>
                        <ul class="chat-sidebar-profile-dropdown">
                            <li>
                                <a href="../account"><i class="ri-user-line"></i> Profile</a>
                            </li>
                            <li>
                                <a href="../logout"><i class="ri-logout-box-line"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <!-- end: Sidebar -->