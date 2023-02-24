<script lang="ts">
    import { onMount } from "svelte";
    import { getSitesAvailable } from "../lib/api";
    import RestartButton from "./RestartButton.svelte";
    import TextEditor from "./TextEditor.svelte";

    let files = [];
    let selectedFilename: string, inputFilename: string;

    onMount(() => {
        getSitesAvailable((json) => {
            files = json;
        });
    });

    function newFile() {
        if (!inputFilename) {
            alert("Enter a valid filename");
            return;
        }
        if (!files.includes(inputFilename)) {
            selectedFilename = inputFilename;
            inputFilename = "";
            files = [...files, selectedFilename];
        } else {
            alert("File already exists");
        }
    }

    function deleteFile() {
        selectedFilename = "";
        getSitesAvailable((json) => {
            files = json;
        });
    }
</script>

<form>
    <select bind:value={selectedFilename} name="filename" id="filename">
        <option value="" disabled hidden> Select a file </option>
        {#each files as item}
            <option value={item}>
                {item}
            </option>
        {/each}
    </select>
    <input bind:value={inputFilename} type="text" />
    <button
        class="btn-primary"
        on:click|preventDefault={newFile}
        on:submit|preventDefault={newFile}
        type="submit"
    >
        New File
    </button>
    <RestartButton />
</form>

{#if selectedFilename}
    <TextEditor filename={selectedFilename} on:delete={deleteFile} />
{/if}
