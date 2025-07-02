#!/bin/bash
BACKUP_DIR=/var/backup/$(date +%F_%H%M)
mkdir -p "$BACKUP_DIR"
tar czf "$BACKUP_DIR/ssh_conf.tgz" /etc/ssh
chown -R devops:devops /var/backup

