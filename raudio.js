(() => {
  const flatten = (obj, roots=[], sep='.') => Object.keys(obj).reduce((memo, prop) => Object.assign({}, memo, Object.prototype.toString.call(obj[prop]) === '[object Object]' ? flatten(obj[prop], roots.concat([prop]), sep) : {[roots.concat([prop]).join(sep)]: obj[prop]}), {});

  class Raudio {
    constructor() {
      this._promise = null;
      this.audio = document.querySelector('audio[data-raudio]');
      this.controller = null;
      this.info = {};

      console.log('RAUDIO');

      // Configure event listeners
      this.audio.addEventListener('play', () => this.renderState());
      this.audio.addEventListener('pause', () => this.renderState());

      document.addEventListener('DOMContentLoaded', () => {
        // See: https://github.com/hotwired/turbo-rails/issues/147

        const autoplay = this.audio.hasAttribute('data-raudio-autoplay') && this.audio.getAttribute('data-raudio-autoplay') !== 'false';

        console.log('autoplay: ???', autoplay);

        if (autoplay) {
          this.audio.play();
        }
      });

      window.addEventListener('click', (event) => {
        const toggle = event.target.getAttribute('data-toggle') === 'raudio' && event.target;

        if (toggle) {
          const audio = this.audio;

          if (audio.paused) {
            audio.play();
          } else {
            audio.pause();
          }

          this.render();
        }
      });

      this.update();
    }

    async fetch() {
      if (this._promise) {
        return this._promise;
      }

      const src = this.audio.src;

      try {
        const fn = [...Raudio.connectors].reduce((result, [test, connector]) => {
          const match = src.match(test);

          if (match) {
            const [, ...args] = match;
            const request = Array.isArray(connector) ? connector[0] : connector;
            const transform = Array.isArray(connector) ? connector[1] : (data) => data;

            return [...result, () => {
              const req = request(...args);

              return [
                typeof req === 'string' ? [req] : req,
                transform,
              ];
            }]
          }
          
          return result;
        }, []).pop();

        if (fn) {
          let [[url, options], transform] = fn();

          const response = fetch(url, options);

          this._promise = response
            .then(
              response => response
                .json()
                .then(result => transform(result))
              )
            .finally(() => {
              this._promise = null;
            });

          return this._promise;
        }
      } catch(e) {
        console.warn(e);
      }
    }

    async update() {
      clearTimeout(this.timeoutId);
      this.fetch()
        .then(result => {
          if (JSON.stringify(this.info) !== JSON.stringify(result)) {
            this.info = result;
            this.render();
          }
        })
        .finally(() => {
          this.timeoutId = setTimeout(() => this.update(), Raudio.interval);
        });
    }

    get displays() {
      return document.querySelectorAll('*[data-raudio]:not(audio)');
    }

    get state() {
      return {
        isPlaying: !this.audio.paused
      }
    }

    renderState() {
      this.displays.forEach(element => element.classList.toggle('is-playing', this.state.isPlaying));
    }

    renderInfo() {
      if (!this.info) {
        return;
      }
      const info = flatten(this.info);
      const prefix = 'raudio';

      this.displays.forEach(container => {
        Object.entries(info).forEach(([key, value]) => {
          const attribute = `data-${prefix}-${key.replace(/[._]+/g, '-')}`;
          const elements = [...container.querySelectorAll(`*[${attribute}]`)];

          elements.forEach(element => {
            if (element instanceof Image) {
              element.src = value;
            } else {
              element.innerHTML = value;
            }
          });

          if (Array.isArray(value)) {
            const keys = [...new Set(value.reduce((result, current) => [...result, ...Object.keys(current)], []))];

            [...Array(25).keys()].forEach(i => {
              const itemAttribute = `${attribute}-${i + 1}`;
              const item = value[i];

              if (item) {
                // Populate array items
                Object.entries(item).forEach(([itemKey, itemValue]) => {
                  const itemKeyAttribute = `${itemAttribute}-${itemKey}`;
                  const itemElements = [...container.querySelectorAll(`*[${itemKeyAttribute}]`)];

                  itemElements.forEach(element => {
                    if (element instanceof Image) {
                      element.src = itemValue;
                    } else {
                      element.innerHTML = itemValue;
                    }
                  });
                });
              } else {
                // Reset unused items
                keys.forEach(itemKey => {
                  const itemKeyAttribute = `${itemAttribute}-${itemKey}`;
                  const itemElements = [...container.querySelectorAll(`*[${itemKeyAttribute}]`)];

                  itemElements.forEach(element => {
                    if (element instanceof Image) {
                      element.src = undefined;
                    } else {
                      element.innerHTML = '';
                    }
                  });
                })
              }
            });
          }
        });
      });
    }

    render() {
      this.renderInfo();
      this.renderState();
    }
  }

  Raudio.connectors = new Map();
  Raudio.interval = 850;

  // radio.co
  Raudio.connectors.set(
    /https\:\/\/stream(?:s|er).radio.co\/(\w+)\/.*/,
    [
      (station) => {
        return `https://public.radio.co/stations/${station}/status`;
      },
      (data) => {
        const [artist, title = data.current_track.title] = data.current_track.title.split(/\s-\s/);
        
        return {
          ...data,
          current_track: {
            ...data.current_track,
            artist: title ? artist : null,
            title: title,
          },
          history: data.history.map(item => {
            const [artist, title = item.title] = item.title.split(/\s-\s/);

            return {
              artist,
              title
            }
          })
        }
      }
    ]
  );

  window.raudio = window.raudio || new Raudio();
})();
