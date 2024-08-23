## Overview
The `Enpii_Base_Bootstrap` class is responsible for the initialization and setup of the Enpii Base framework within a WordPress environment. It includes methods to handle both CLI and web mode operations, ensuring the proper setup of WordPress application hooks, redirections, and actions.

#### Description:
The `initialize` method serves as the entry point for setting up the Enpii Base framework. It is triggered early in the `enpii-base-init.php` file. This method checks whether the WordPress content directory is loaded and then performs the necessary setup actions based on the environment, handling both CLI and web modes appropriately.

#### Function Flow:
1. **Check if WordPress Content Directory is Loaded:**
   - Uses `Enpii_Base_Helper::is_wp_content_loaded()` to verify if the WordPress content directory is loaded.
   - If not loaded, the method terminates early.

2. **Handle Command-Line Interface (CLI) Mode:**
   - Checks if the environment is running in CLI mode with `Enpii_Base_Helper::is_console_mode()`.
   - Registers the CLI initialization action via `static::register_cli_init_action()` to bind it to the `cli_init` hook. This method is only relevant when the application is running in CLI mode, using `Enpii_Base_Helper::wp_cli_init()` as the callback function.
   - Checks the `$_SERVER['argv']`, if the `'enpii-base prepare'` command is detected using `static::is_enpii_base_prepare_command()`, it prepares the WP App folders with `Enpii_Base_Helper::prepare_wp_app_folders()` and exits early.

3. **Web Mode Setup:**
   - If not in CLI mode, use `Enpii_Base_Helper::perform_wp_app_check()` method to verify whether the necessary extensions and WordPress application setup steps are correct. If the check fails, it exits early.
   - Registers a redirect action that is triggered during the application setup process using `static::register_setup_app_redirect()`. This method ensures that users are redirected to the appropriate setup page. This adds an action to the `ENPII_BASE_SETUP_HOOK_NAME` hook, using `Enpii_Base_Helper::maybe_redirect_to_setup_app()` as the callback function. The priority of this action is set to `-200`.
   - Registers hooks necessary for the proper setup of the WP App via `static::register_wp_app_setup_hooks()`. This method ensures that the WP App instance is loaded during the setup process, it adds an action to the `ENPII_BASE_SETUP_HOOK_NAME` hook, using `\Enpii_Base\App\WP\WP_Application::load_instance()` as the callback function. The priority of this action is set to `-100`
   - Signals that the WP App has fully loaded by registering the `register_wp_app_loaded_action()`. This method is called after all necessary setup actions are completed, it adds an action to the `\Enpii_Base\App\Support\App_Const::ACTION_WP_APP_LOADED` hook, using `static::handle_wp_app_loaded_action()` as the callback function, which initializes the Enpii Base WordPress plugin with the necessary configurations by calling `\Enpii_Base\App\WP\Enpii_Base_WP_Plugin::init_with_wp_app()` with the plugin slug, directory, and URL parameters.

## Summary
The `Enpii_Base_Bootstrap` class provides essential methods to initialize and configure the Enpii Base framework within a WordPress environment. It handles different setups for CLI and web modes, ensuring that the application is correctly configured and ready to be used.
