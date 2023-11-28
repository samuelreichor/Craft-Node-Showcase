echo "Start ddev containers:"
ddev start

COMPOSER_DIR=~/.composer
if test -d "$COMPOSER_DIR"; then
    echo "Symlink composer settings."
    ln -s ~/.composer .ddev/homeadditions/.composer
fi

echo "Setup Craft CMS:"
ddev craft setup

if  command -v nvm !&> /dev/null
then
    echo "Set correct Node.js version:"
    . ~/.nvm/nvm.sh
    nvm use
fi

echo "Launch website:"
ddev launch

# Normal exit
exit 0