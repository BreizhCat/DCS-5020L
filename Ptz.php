<?php
/**
 * Class to control the D-Link DCS-5222L
 *
 * @author Tim de Pater <code AT trafex DOT nl>
 */
class Ptz
{
    const POSITION_COMMAND = 'set_relative_pos';
    const STOP_COMMAND = 'stop';
    const PAN_COMMAND = 'pan_patrol';
    const PATROL_COMMAND = 'user_patrol';
    const PRESET_COMMAND = 'goto_preset_position';

    const XPOS = 'xpos';
    const YPOS = 'ypos';
    const PRESET_ID = 'presetId';
    const DEFAULT_STEPSIZE = 10;

    protected $host;
    protected $user;
    protected $password;
    protected $useSsl = false;
    protected $ptzUrl;
    protected $baseUrl;

    protected $xmlDoc;

    public function __construct($host, $user, $password, $settings = array())
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;

        if (isset($settings['ssl'])) {
            $this->useSsl = $settings['ssl'];
        }

        $scheme = 'http';
        if ($this->useSsl) {
            $scheme = 'https';
        }
        $this->ptzUrl = sprintf(
            '%s://%s:%s@%s/cgi/ptdc.cgi?',
            $scheme,
            $this->user,
            $this->password,
            $this->host
        );
        $this->baseUrl = sprintf(
            '%s://%s:%s@%s',
            $scheme,
            $this->user,
            $this->password,
            $this->host
        );
    }

    public function setPosition($x, $y)
    {
        $params = http_build_query(
            array(
                'command' => self::POSITION_COMMAND,
                'posX' => $x,
                'posY' => $y,
            )
        );
        $this->request($this->ptzUrl . $params);
    }

    public function getPositions($stepSize = null)
    {
        if (null === $stepSize) {
            $stepSize = self::DEFAULT_STEPSIZE;
        }
        $options = array(
            'Gauche' => array(
                'xpos' => -$stepSize,
                'ypos' => 0,
            ),
            'Droite' => array(
                'xpos' => $stepSize,
                'ypos' => 0,
            ),
            'Haut' => array(
                'xpos' => 0,
                'ypos' => $stepSize,
            ),
            'Bas' => array(
                'xpos' => 0,
                'ypos' => -$stepSize,
            ),
        );
        return array_map(
            function ($value) {
                return '?' . http_build_query(
                    array_merge(
                        array('command' => Ptz::POSITION_COMMAND),
                        $value
                    )
                );
            },
            $options
        );
    }

    public function setPreset($id)
    {
        $params = http_build_query(
            array(
                'command' => self::PRESET_COMMAND,
                'index' => $id,
            )
        );
        $this->request($this->ptzUrl . $params);
    }

    protected function request($url)
    {
        return file_get_contents($url);
    }
}
