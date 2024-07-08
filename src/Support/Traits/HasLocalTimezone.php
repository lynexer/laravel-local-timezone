<?php

namespace Lynexer\LaravelLocalTimezone\Support\Traits;

trait HasLocalTimezone {
    public function getTimezone(): ?string {
        return $this->timezone !== null ? (string) $this->timezone : null;
    }

    public function getTimezoneOverride(): bool {
        return $this->override_timezone;
    }

    public function setTimezone(?string $timezone): self {
        $this->timezone = $timezone;

        return $this;
    }

    public function setTimezoneOverride(?bool $should_override): self {
        $this->override_timezone = $should_override ?? false;

        return $this;
    }
}
