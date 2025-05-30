<?php

add_action('admin_menu', 'tk_add_admin_menu');

function tk_add_admin_menu() {
    add_menu_page(
        'GitHub Build Trigger',
        'Build Trigger',
        'manage_options',
        'github-build-trigger',
        'gbt_admin_page'
    );
}

function tk_admin_page() {
?>
<div class="wrap">
  <h1>GitHub Build Trigger</h1>
  <form method="post">
    <?php submit_button('Build auslösen'); ?>
  </form>
  <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST'):
            tk_trigger_webhook();
        endif;
        ?>
</div>
<?php
}

function tk_trigger_webhook() {
    $webhook_url = GITHUB_WEBHOOK; 
    $token = GITHUB_TOKEN;

    $body = json_encode([
        'event_type' => 'wp_build_trigger'
    ]);

    $response = wp_remote_post($webhook_url, [
        'headers' => [
            'Authorization' => 'token ' . $token,
            'Accept'        => 'application/vnd.github.everest-preview+json',
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'WordPressWebhookTrigger'
        ],
        'body' => $body
    ]);

    if (is_wp_error($response)):
        echo '<p style="color:red;">Error: ' . $response->get_error_message() . '</p>';
    else:
        echo '<p style="color:green;">Build-Webhook triggered.</p>';
    endif;


}
