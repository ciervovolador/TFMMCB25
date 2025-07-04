FROM dorowu/ubuntu-desktop-lxde-vnc
RUN rm -f /etc/apt/sources.list.d/google-chrome.list && \
    apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
      firefox remmina thunderbird net-tools curl dnsutils openssh-client nmap && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
