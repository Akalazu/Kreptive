<?php
require_once 'config.php';
?>
<!-- start: Content -->
<div class="chat-content">
    <!-- start: Content side -->
    <div class="content-sidebar">
        <div class="content-sidebar-title">Chats</div>
        <form action="" class="content-sidebar-form">
            <input
                type="search"
                class="content-sidebar-input"
                placeholder="Search..." />
            <button type="submit" class="content-sidebar-submit">
                <i class="ri-search-line"></i>
            </button>
        </form>
        <div class="content-messages">
            <ul class="content-messages-list" id="chatList">
                <!-- Skeleton loader for the li -->
                <li class="skeleton">
                    <div class="skeleton-image"></div>
                    <span class="skeleton-info">
                        <span class="skeleton-name"></span>
                        <span class="skeleton-text"></span>
                    </span>
                    <span class="skeleton-more">
                        <span class="skeleton-unread"></span>
                        <span class="skeleton-time"></span>
                    </span>

                </li>
            </ul>


        </div>
    </div>
    <!-- end: Content side -->
    <!-- start: Conversation -->
    <div class="conversation conversation-default active">
        <i class="ri-chat-3-line"></i>
        <p>Select chat and view conversation!</p>
    </div>
    <div class="conversation" id="conversation-1">
        <div class="conversation-top">
            <button type="button" class="conversation-back">
                <i class="ri-arrow-left-line"></i>
            </button>
            <div class="conversation-user">
                <img
                    class="conversation-user-image"
                    src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60"
                    alt="" />
                <div>
                    <div class="conversation-user-name">Someone</div>
                    <div class="conversation-user-status online">online</div>
                </div>
            </div>

        </div>
        <div class="conversation-main">
            <ul class="conversation-wrapper">
                <div class="coversation-divider"><span>Today</span></div>

            </ul>
        </div>
        <div class="conversation-form">
            <input type="hidden" id="sender_id" value="<?= $currUser->id ?>" />
            <input type="hidden" id="receiver_id" value="<?= $currUser->id ?>" />

            <!-- Sender ID, dynamically set -->
            <input type="hidden" id="chat_id" value="1" />
            <!-- Chat ID, dynamically set -->
            <!-- <button type="button" class="conversation-form-button">
        <i class="ri-emotion-line"></i>
      </button> -->
            <div class="conversation-form-group">
                <textarea
                    class="conversation-form-input" id="message_content"
                    rows="1"
                    placeholder="Type here..."></textarea>
                <!-- <button type="button" class="conversation-form-record">
          <i class="ri-mic-line"></i>
        </button> -->
            </div>
            <button
                type="button"
                class="conversation-form-button conversation-form-submit" id="send_button">
                <i class="ri-send-plane-2-line"></i>
            </button>
        </div>
    </div>

    <!-- end: Conversation -->
</div>


<?php
require_once 'footer.php'
?>