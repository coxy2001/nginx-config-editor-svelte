<script lang="ts">
    import { createEventDispatcher } from "svelte";

    export let text: string;

    let serverName: string, proxyPass: string;

    const dispatch = createEventDispatcher();

    $: {
        let serverNameParts = text.split("server_name ")[1];
        if (serverNameParts) serverName = serverNameParts.split(";")[0];
        else serverName = "";

        let proxyPassParts = text.split("proxy_pass ")[1];
        if (proxyPassParts) proxyPass = proxyPassParts.split(";")[0];
        else proxyPass = "";
    }

    function save() {
        dispatch("helloworld");
        dispatch("close", {
            text: `server {
    listen 80;
    listen [::]:80;

    server_name ${serverName};

    location / {
        proxy_pass ${proxyPass};
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $remote_addr;
    }
}
`,
        });
    }

    function close() {
        dispatch("close");
    }
</script>

<div class="proxy-editor">
    <div class="proxy-editor__input-row">
        <div class="proxy-editor__input-group">
            <label for="server_name">Server Name:</label>
            <input
                type="text"
                name="server_name"
                id="server_name"
                bind:value={serverName}
            />
        </div>
        <div class="proxy-editor__input-group">
            <label for="proxy_pass">Proxy Pass:</label>
            <input
                type="text"
                name="proxy_pass"
                id="proxy_pass"
                bind:value={proxyPass}
            />
        </div>
    </div>
    <div class="proxy-editor__buttons">
        <button class="btn-success" on:click={save}>Save as Proxy</button>
        <button class="btn-danger" on:click={close}>Cancel</button>
    </div>
</div>
