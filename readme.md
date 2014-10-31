#User guide (beta)#

---

        jb_api_yahoo_weather:
            woeid: 123456
            unit: c
            
        aequasi_cache:
            instances:
                jb_api_yahoo_weather:
                  persistent: false # Boolean or persistent_id
                  namespace: jayw
                  type: memcached
                  hosts:
                      - { host: localhost, port: 11211 }
              
---

        "fabpot/goutte": "~2.0",
        "aequasi/cache-bundle": "dev-master"