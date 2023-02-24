<script lang="ts">
    import { createEventDispatcher } from "svelte";
    import ProxyEditor from "./ProxyEditor.svelte";
    import {
        deleteFile,
        disableFile,
        enableFile,
        getFile,
        saveFile,
    } from "../lib/api";

    export let filename;

    let text: string,
        loading: boolean,
        proxy = false;

    const dispatch = createEventDispatcher();

    $: {
        filename;
        text = "Loading...";
        getText();
    }

    function getText() {
        loading = true;
        getFile(filename, (result) => {
            text = result;
            loading = false;
        });
    }

    function buttonSave_OnClick() {
        if (!loading) {
            saveFile(filename, text);
        }
    }

    function buttonRefresh_OnClick() {
        if (!loading) {
            getText();
        }
    }

    function buttonEnable_OnClick() {
        if (!loading) {
            enableFile(filename);
        }
    }

    function buttonDisable_OnClick() {
        if (!loading) {
            disableFile(filename);
        }
    }

    function buttonDelete_OnClick() {
        if (!loading && window.confirm("Do you want to delete this file?")) {
            deleteFile(filename);
            dispatch("delete");
        }
    }

    function proxySave(e) {
        if (e.detail && e.detail.text) text = e.detail.text;
        proxy = false;
    }
</script>

{#if proxy}
    <ProxyEditor {text} on:close={proxySave} />
{:else}
    <div class="text-editor">
        <textarea bind:value={text} />

        <div class="text-editor__buttons">
            <button
                class={"btn-success" + (loading ? " btn--disabled" : "")}
                on:click={buttonSave_OnClick}
            >
                Save
            </button>
            <button
                class={"btn-primary" + (loading ? " btn--disabled" : "")}
                on:click={buttonRefresh_OnClick}
            >
                Refresh
            </button>
            <button
                class={"btn-primary" + (loading ? " btn--disabled" : "")}
                on:click={buttonEnable_OnClick}
            >
                Enable
            </button>
            <button
                class={"btn-primary" + (loading ? " btn--disabled" : "")}
                on:click={buttonDisable_OnClick}
            >
                Disable
            </button>
            <button
                class={"btn-danger" + (loading ? " btn--disabled" : "")}
                on:click={buttonDelete_OnClick}
            >
                Delete
            </button>
            <div class="text-editor__buttons-right">
                <button
                    class={"btn-primary" + (loading ? " btn--disabled" : "")}
                    on:click={() => (proxy = true)}
                >
                    Switch Editor
                </button>
            </div>
        </div>
    </div>
{/if}
