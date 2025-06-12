<?php

/**
 * Error handling helper for dashboards
 * This page displays an error message in a styled way
 */

// Security check - this should only be included, not accessed directly
if (!defined('DASHBOARD_ERROR')) {
    header('Location: /StudentGrader/');
    exit;
}

?>

<div class="error-container">
    <div class="error-icon">⚠️</div>
    <div class="error-message">
        <h3>Error</h3>
        <p><?= htmlspecialchars($error_message) ?></p>

        <div class="error-actions">
            <a href="/StudentGrader/logout" class="btn btn-primary">Log out</a>
            <a href="javascript:window.location.reload()" class="btn">Try again</a>
            <a href="/StudentGrader/session-debug" class="btn">Debug session</a>
        </div>

        <?php if (isset($error_details) && !empty($error_details)): ?>
            <div class="error-details">
                <details>
                    <summary>Technical details</summary>
                    <pre><?= htmlspecialchars($error_details) ?></pre>
                </details>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .error-container {
        background-color: #fff3f3;
        border: 1px solid #ffcccb;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        display: flex;
        align-items: flex-start;
    }

    .error-icon {
        font-size: 2em;
        margin-right: 20px;
    }

    .error-message {
        flex-grow: 1;
    }

    .error-message h3 {
        margin-top: 0;
        color: #d32f2f;
    }

    .error-actions {
        margin-top: 20px;
    }

    .error-actions .btn {
        display: inline-block;
        padding: 8px 16px;
        margin-right: 10px;
        text-decoration: none;
        border-radius: 4px;
        color: #333;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
    }

    .error-actions .btn-primary {
        background-color: #4caf50;
        border-color: #43a047;
        color: white;
    }

    .error-details {
        margin-top: 20px;
        color: #555;
    }

    .error-details pre {
        background-color: #f8f8f8;
        padding: 10px;
        border-radius: 4px;
        overflow-x: auto;
    }
</style>