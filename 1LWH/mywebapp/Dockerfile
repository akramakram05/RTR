FROM debian:12-slim

RUN apt-get update && apt-get install -y --no-install-recommends openssh-server cron sudo net-tools iputils-ping curl wget unzip less && rm -rf /var/lib/apt/lists/*

RUN useradd -m -u 1000 -s /bin/bash devops && echo "devops:devops" | chpasswd && echo "devops ALL=(ALL) NOPASSWD:ALL" >/etc/sudoers.d/devops

RUN mkdir -p /run/sshd && echo "devops:devops" | chpasswd && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

RUN mkdir -p /var/backup && chown devops:devops /var/backup

COPY entrypoint.sh /entrypoint.sh
COPY backup.sh /usr/local/bin/backup.sh
RUN chmod +x /entrypoint.sh /usr/local/bin/backup.sh

RUN echo "0 2 * * * root /usr/local/bin/backup.sh" >/etc/cron.d/backup && chmod 0644 /etc/cron.d/backup && crontab /etc/cron.d/backup

EXPOSE 20
EXPOSE 21
EXPOSE 22
EXPOSE 80
EXPOSE 3306
EXPOSE 443

ENTRYPOINT ["/entrypoint.sh"]

