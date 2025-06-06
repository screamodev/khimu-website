<div
    class="jet-popup-settings-manager wp-admin"
>
	<div class="jet-popup-settings-manager__container">
		<div class="jet-popup-settings-manager__blank">
			<div class="jet-popup-settings-manager__blank-title"><?php echo __( 'Set the popup settings', 'jet-popup' ); ?></div>
			<div class="jet-popup-settings-manager__blank-message">
				<span><?php echo __( 'Here you can customize the popup by specifying the animation, opening trigger, overlay settings, close buttons and more.', 'jet-popup' ); ?></span>
			</div>
		</div>
        <div class="jet-popup-settings-manager__loader" v-if="getSettingsStatus">
            <span class="jet-popup-library__spinner-loader">
                <svg width="16" class="loader-icon" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.9023 9.06541L14.4611 9.55376C14.3145 10.2375 14.0214 10.8967 13.6305 11.5316L14.2901 12.899C14.3145 12.9478 14.2901 12.9966 14.2656 13.0455L12.971 14.3396C12.9221 14.364 12.8733 14.3884 12.8244 14.364L11.4565 13.7047C10.8458 14.0954 10.1863 14.364 9.47786 14.5105L8.98931 15.9512C8.98931 15.9756 8.96489 16 8.8916 16H7.08397C7.03511 16 6.98626 15.9756 6.96183 15.9267L6.47328 14.4861C5.78931 14.3396 5.12977 14.0466 4.49466 13.6559L3.12672 14.3152C3.07786 14.3396 3.02901 14.3152 2.98015 14.2908L1.6855 12.9966C1.66107 12.9478 1.63664 12.899 1.66107 12.8501L2.32061 11.4828C1.92977 10.8723 1.66107 10.213 1.5145 9.50493L0.0732824 9.01658C0.0244275 8.99216 0 8.96774 0 8.89449L0 7.08759C0 7.03875 0.0244275 6.98992 0.0732824 6.9655L1.5145 6.47715C1.66107 5.79346 1.9542 5.13418 2.34504 4.49933L1.6855 3.13194C1.66107 3.08311 1.6855 3.03427 1.70992 2.98544L3.00458 1.69131C3.05344 1.66689 3.10229 1.64247 3.15114 1.66689L4.51908 2.32616C5.12977 1.93548 5.78931 1.66689 6.49771 1.52038L6.98626 0.0797482C7.01069 0.0309124 7.03511 0.00649452 7.1084 0.00649452L8.91603 0.00649452C8.96489 -0.0179234 9.01374 0.0309124 9.03817 0.0797482L9.52672 1.52038C10.2107 1.66689 10.8702 1.9599 11.5053 2.35058L12.8733 1.69131C12.9221 1.66689 12.971 1.69131 13.0198 1.71572L14.3145 3.00986C14.3389 3.05869 14.3634 3.10753 14.3389 3.15636L13.6794 4.52374C14.0702 5.13418 14.3389 5.79346 14.4855 6.50157L15.9267 6.98992C15.9756 7.01434 16 7.03875 16 7.11201V8.91891C15.9756 8.99216 15.9511 9.04099 15.9023 9.06541ZM11.5786 6.9655C10.9924 4.98768 8.91603 3.86447 6.96183 4.45049C4.98321 5.03651 3.85954 7.11201 4.4458 9.06541C5.03206 11.0432 7.1084 12.1664 9.0626 11.5804C11.0412 11.0188 12.1649 8.94332 11.5786 6.9655Z"/></svg>
            </span>
        </div>
		<div class="jet-popup-settings-manager__settings" v-if="!getSettingsStatus">
            <cx-vui-select
                name="jet-popup-animation"
                label="<?php _e( 'Animation', 'jet-popup' ); ?>"
                description="<?php _e( 'Choose animation effect for popup.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="animationTypeOptions"
                v-model="settings['jet_popup_animation']"
            >
            </cx-vui-select>
            <cx-vui-select
                name="jet-popup-open-trigger"
                label="<?php _e( 'Open Event', 'jet-popup' ); ?>"
                description="<?php _e( 'Choose popup open event.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="triggerTypeOptions"
                v-model="settings['jet_popup_open_trigger']"
            >
            </cx-vui-select>
            <cx-vui-input
                name="jet-popup-page-load-delay"
                label="<?php _e( 'Open Delay(s)', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter a delay after which the popup will appear after the page is loaded.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="number"
                :min="0"
                :max="60"
                :step="1"
                v-model="settings['jet_popup_page_load_delay']"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'page-load',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-user-inactivity-time"
                label="<?php _e( 'User Inactivity Time(s)', 'jet-menu' ); ?>"
                description="<?php _e( 'enter the time of user inactivity after which the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="number"
                :min="0"
                :max="60"
                :step="1"
                v-model="settings['jet_popup_user_inactivity_time']"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'user-inactive',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-scrolled-to-value"
                label="<?php _e( 'Page Scroll Progress(%)', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the scrolling percentage of the page on which the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="number"
                :min="0"
                :max="100"
                :step="1"
                v-model="settings['jet_popup_scrolled_to_value']"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'scroll-trigger',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-on-date-value"
                label="<?php _e( 'Open Date', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the date when the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="date"
                v-model="onDateValue"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-date',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-on-date-time-value"
                label="<?php _e( 'Open Time', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the time when the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="time"
                v-model="onDateTimeValue"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-date',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-on-time-start-value"
                label="<?php _e( 'Start Time', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the time when the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="time"
                v-model="settings['jet_popup_on_time_start_value']"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-time',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-on-time-end-value"
                label="<?php _e( 'End Time', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the time when the popup will will disappear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="time"
                v-model="settings['jet_popup_on_time_end_value']"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-time',
                }]"
            >
            </cx-vui-input>

            <cx-vui-input
                name="jet-popup-on-date-start"
                label="<?php _e( 'Start Date', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the date when the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="date"
                v-model="onDateValueStart"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-date-and-time',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-on-date-time-start"
                label="<?php _e( 'Start Time', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the time when the popup will appear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="time"
                v-model="onDateTimeValueStart"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-date-and-time',
                }]"
            >
            </cx-vui-input>

            <cx-vui-input
                name="jet-popup-on-date-end"
                label="<?php _e( 'End Date', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the date when the popup will disappear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="date"
                v-model="onDateValueEnd"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-date-and-time',
                }]"
            >
            </cx-vui-input>
            <cx-vui-input
                name="jet-popup-on-date-time-end"
                label="<?php _e( 'End Time', 'jet-menu' ); ?>"
                description="<?php _e( 'Enter the time when the popup will disappear.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="time"
                v-model="onDateTimeValueEnd"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'on-date-and-time',
                }]"
            >
            </cx-vui-input>

            <cx-vui-input
                name="jet-popup-custom-selector"
                label="<?php _e( 'Custom Selector', 'jet-menu' ); ?>"
                description="<?php _e( 'Set a custom selector on which a popup will appear when clicked.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="text"
                v-model="settings['jet_popup_custom_selector']"
                :conditions="[{
                    input: this.settings['jet_popup_open_trigger'],
                    compare: 'equal',
                    value: 'custom-selector',
                }]"
            >
            </cx-vui-input>
            <cx-vui-select
                name="jet-popup-on-сlose-event"
                label="<?php _e( 'On Close Event', 'jet-popup' ); ?>"
                description="<?php _e( 'Choose event on сlose popup.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="triggerTypeCloseOptions"
                v-model="settings['jet_popup_on_close_event']"
            >
            </cx-vui-select>
            <cx-vui-input
                name="jet-popup-scroll-to-anchor"
                label="<?php _e( 'Anchor ID', 'jet-menu' ); ?>"
                description="<?php _e( 'Set up anchor ID scrolling to block page.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="text"
                v-model="settings['jet_popup_scroll_to_anchor']"
                :conditions="[{
                    input: this.settings['jet_popup_on_close_event'],
                    compare: 'equal',
                    value: 'scroll-to-anchor',
                }]"
            >
            </cx-vui-input>
            <cx-vui-switcher
                name="jet-popup-prevent-scrolling"
                label="<?php _e( 'Prevent Page Scrolling', 'jet-popup' ); ?>"
                description="<?php _e( 'Enable to block page scrolling. Close popup to continue page scrolling.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['jet_popup_prevent_scrolling']"
            >
            </cx-vui-switcher>
            <cx-vui-switcher
                name="jet-popup-show-once"
                label="<?php _e( 'Show Once', 'jet-popup' ); ?>"
                description="<?php _e( 'When closing the popup, when retriggered, it will not appear again', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['jet_popup_show_once']"
            >
            </cx-vui-switcher>
            <cx-vui-select
                name="jet-popup-show-again-delay"
                label="<?php _e( 'Repeat Showing Popup In', 'jet-popup' ); ?>"
                description="<?php _e( 'Set the timeout caching and a popup will be displayed again.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="timeDelayTypeOptions"
                v-model="settings['jet_popup_show_again_delay']"
                :conditions="[{
                    input: this.settings['jet_popup_show_once'],
                    compare: 'equal',
                    value: 'yes',
                }]"
            >
            </cx-vui-select>
            <cx-vui-switcher
                name="jet-popup-use-ajax"
                label="<?php _e( 'Loading content with Ajax', 'jet-popup' ); ?>"
                description="<?php _e( 'When using ajax, the content of the popup will be loaded after the popup appears. This allows you to increase the loading speed of the site.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['jet_popup_use_ajax']"
            >
            </cx-vui-switcher>
            <cx-vui-switcher
                name="jet-popup-force-ajax"
                label="<?php _e( 'Force Loading', 'jet-popup' ); ?>"
                description="<?php _e( 'Force Loading every time you open the popup.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['jet_popup_force_ajax']"
                :conditions="[{
                    input: this.settings['jet_popup_use_ajax'],
                    compare: 'equal',
                    value: 'yes',
                }]"
            >
            </cx-vui-switcher>
            <cx-vui-switcher
                name="use-close-button"
                label="<?php _e( 'Use Close Button', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['use_close_button']"
            >
            </cx-vui-switcher>
            <cx-vui-wp-media
                label="<?php _e( 'SVG Icon', 'jet-popup' ); ?>"
                name="close-button-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="settings['close_button_icon']"
                v-if="settings['use_close_button'] && isCloseIconSettingVisible"
            ></cx-vui-wp-media>
            <cx-vui-switcher
                name="use-overlay"
                label="<?php _e( 'Use Overlay', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['use_overlay']"
            >
            </cx-vui-switcher>
            <cx-vui-switcher
                name="close-on-overlay-click"
                label="<?php _e( 'Close On Overlay Click', 'jet-popup' ); ?>"
                description="<?php _e( 'Сlose the popup when clicking on the overlay.', 'jet-popup' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="yes"
                return-false="no"
                v-model="settings['close_on_overlay_click']"
                :conditions="[{
                    input: this.settings['use_overlay'],
                    compare: 'equal',
                    value: 'yes',
                }]"
            >
            </cx-vui-switcher>

		</div>
	</div>
	<div class="jet-popup-settings-manager__controls">
        <cx-vui-button
            button-style="default"
            class="cx-vui-button--style-accent-border"
            size="mini"
            @click="closeSettingsManagerPopupHandler"
        >
            <template v-slot:label>
                <span><?php echo __( 'Cancel', 'jet-popup' ); ?></span>
            </template>
        </cx-vui-button>
		<cx-vui-button
            button-style="default"
            class="cx-vui-button--style-accent"
			:loading="saveSettingsStatus"
			size="mini"
			@click="saveSettings"
		>
            <template v-slot:label>
                <span><?php echo __( 'Save Settings', 'jet-popup' ); ?></span>
            </template>
		</cx-vui-button>
	</div>
</div>
