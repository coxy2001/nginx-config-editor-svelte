<script lang="ts">
    import { serverRestart } from "../lib/api";
</script>

<button
    class="btn-danger"
    on:click|preventDefault={() => {
        serverRestart((json) => {
            if (json.status !== "ok") alert(json.message);
        });
    }}
>
    Restart Webserver
</button>
