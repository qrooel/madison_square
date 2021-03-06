<?php defined('ROOTPATH') OR die('No direct access allowed.');
/*
 *  $Id: MySQLPreparedStatement.php 6 2011-03-27 19:01:27Z gekosale $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://creole.phpdb.org>.
 */
 
require_once ROOTPATH.'/libs/creole/PreparedStatement.php';
require_once ROOTPATH.'/libs/creole/common/PreparedStatementCommon.php';

/**
 * MySQL subclass for prepared statements.
 * 
 * @author    Hans Lellelid <hans@xmpl.org>
 * @version   $Revision: 6 $
 * @package   creole.drivers.mysql
 */
class MySQLPreparedStatement extends PreparedStatementCommon implements PreparedStatement {        
    
    /**
     * Quotes string using native mysql function (mysql_real_escape_string()).
     * @param string $str
     * @return string
     */
    protected function escape($str)
    {
        return mysql_real_escape_string($str, $this->conn->getResource());
    }    
    
}
