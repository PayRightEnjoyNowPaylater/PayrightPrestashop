# PrestaShop Add-On

## Publishing
To publish the files as a .zip file for upload at addons.prestashop.com, run the following command on the root folder of this project:

`./build.sh -v <VERSION-NO>`

For example:

`./build.sh -v 1.0.5`

This will zip the necessary files into (in this example), `dist/payright-1.0.5.zip`

Follow the instructions on the [PayRight Wiki](https://payrightmel.atlassian.net/wiki/spaces/DP/pages/116654085/Publishing+the+PayRight+add-on+to+PrestaShop) for how to publish the add-on.

## Local development
For local development, you can first install the plugin on your local PrestaShop installation. You can then rewrite the installed add-on with your version controlled copy

- Build the zip file using the command above
- Upload the zip file to your local PrestaShop installation
- You will notice the add-on located in your PrestaShop installation as `<prestashop-installation>/src/modules/payright`
- First, remove that folder: `rm -r <prestashop-installation>/src/modules/payright`
- Now copy your entire git project into `<prestashop-installation>/src/modules/payright`
- Now you can edit the files directly in `<prestashop-installation>/src/modules/payright`, and use git to pull, commit, push, etc