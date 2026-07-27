## vpnbot-mini

Lightweight fork of [mercurykd/vpnbot](https://github.com/mercurykd/vpnbot):

- WireGuard, AmneziaWG 2.0
- VLESS (Reality, WebSocket, XHTTP)
- WARP, PAC, SSL

Removed: OpenConnect, NaiveProxy, MTProto, Shadowsocks, Hysteria, DNSTT.

---

#### Install:

```shell
wget -O- https://raw.githubusercontent.com/igorkass/vpnbot-mini/master/scripts/init.sh | sh -s YOUR_TELEGRAM_BOT_KEY
```
#### Restart:
```shell
make r
```
#### Autoload:
```shell
crontab -e
```
Add and save:
```shell
@reboot cd /root/vpnbot-mini && make r
```

Environment: Ubuntu 22.04/24.04, Debian 11/12/13.
