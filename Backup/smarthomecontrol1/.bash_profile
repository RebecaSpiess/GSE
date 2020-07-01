# .bash_profile

# Get the aliases and functions
if [ -f ~/.bashrc ]; then
	. ~/.bashrc
fi

# User specific environment and startup programs

PATH=$PATH:$HOME/bin

export PATH
export GEM_HOME="/home/smarthomecontrol1/.gems"
export PATH="$HOME/bin:$HOME/.gems/bin:$PATH"
export PATH